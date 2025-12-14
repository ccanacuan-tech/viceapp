<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleDriveController extends Controller
{
    public function connect()
    {
        $client = new Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));
        $client->setScopes([
            Drive::DRIVE_FILE,
            Drive::DRIVE,
        ]);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        return redirect($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = new Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // Store the refresh token in the .env file
        if (isset($token['refresh_token'])) {
            $this->updateDotEnv('GOOGLE_REFRESH_TOKEN', $token['refresh_token']);
        }

        return redirect('/plannings')->with('success', 'Google Drive conectado correctamente!');
    }

    public function picker()
    {
        $client = new Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRefreshToken(env('GOOGLE_REFRESH_TOKEN'));
        $client->fetchAccessTokenWithRefreshToken();
        $accessToken = $client->getAccessToken();

        if (!$accessToken) {
            return redirect()->route('google.connect')->with('error', 'No se pudo obtener el token de acceso de Google. Por favor, vuelve a conectarte.');
        }

        return view('google-drive.picker', [
            'accessToken' => $accessToken['access_token'],
            'developerKey' => config('google.developer_key'),
            'clientId' => config('google.client_id'),
        ]);
    }

    /**
     * Update the .env file with the given key and value.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    protected function updateDotEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            $oldValue = env($key);

            if (strpos($content, $key . '=') !== false) {
                $content = str_replace($key . '=' . $oldValue, $key . '=' . $value, $content);
            } else {
                $content .= "\n" . $key . '=' . $value;
            }

            file_put_contents($path, $content);
        }
    }
}
