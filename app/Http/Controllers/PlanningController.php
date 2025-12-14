<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Drive;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $query = Planning::where('user_id', Auth::id());

        if ($request->has('search') && $request->input('search') != '') {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $plannings = $query->latest()->paginate(10);
        $googleDriveConnected = !empty(env('GOOGLE_REFRESH_TOKEN'));

        return view('plannings.index', compact('plannings', 'googleDriveConnected'));
    }

    public function adminIndex(Request $request)
    {
        $query = Planning::with('user');

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                  });
            });
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $plannings = $query->latest()->paginate(10);

        return view('plannings.admin-index', compact('plannings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => [
                'required_without:google_drive_file_id',
                'file',
                'max:2048', // 2MB Max
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
            'google_drive_file_id' => 'required_without:file|string',
        ]);

        $path = null;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('plannings', 'public');
        } elseif ($request->has('google_drive_file_id')) {
            $fileId = $request->input('google_drive_file_id');

            $client = new Client();
            $client->setClientId(config('google.client_id'));
            $client->setClientSecret(config('google.client_secret'));
            $client->setRefreshToken(env('GOOGLE_REFRESH_TOKEN'));
            $client->fetchAccessTokenWithRefreshToken();

            $service = new Drive($client);
            $file = $service->files->get($fileId, ['alt' => 'media']);

            $fileName = $request->input('title');
            // Sanitize filename
            $safeFileName = preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
            $path = 'plannings/' . time() . '_' . $safeFileName;

            Storage::disk('public')->put($path, $file->getBody()->getContents());
        }

        if ($path) {
            Planning::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'file_path' => $path,
            ]);

            return redirect()->route('plannings.index')->with('success', 'Planificación subida exitosamente.');
        }

        return redirect()->back()->with('error', 'No se pudo subir la planificación.');
    }

    public function download(Planning $planning)
    {
        if (Auth::user()->hasRole('secretaria') || $planning->user_id === Auth::id()) {
            return Storage::disk('public')->download($planning->file_path);
        }
        abort(403);
    }

    public function view(Planning $planning)
    {
        if (Auth::user()->hasRole('secretaria') || $planning->user_id === Auth::id()) {
            $planning->load('comments.user');
            return view('plannings.view', compact('planning'));
        }
        abort(403);
    }

    public function updateStatus(Request $request, Planning $planning)
    {
        $request->validate([
            'status' => 'required|in:borrador,revisión,aprobado,rechazado',
        ]);

        // Lógica de permisos
        $user = Auth::user();
        $currentStatus = $planning->status;
        $newStatus = $request->status;

        if ($user->hasRole('docente')) {
            // El docente solo puede enviar a revisión o volver a borrador
            if (!(($currentStatus === 'borrador' && $newStatus === 'revisión') || 
                  ($currentStatus === 'rechazado' && $newStatus === 'revisión'))) {
                abort(403, 'Acción no permitida.');
            }
        } elseif ($user->hasRole('secretaria')) {
            // La secretaría solo puede aprobar o rechazar
            if ($currentStatus !== 'revisión') {
                abort(403, 'Solo se pueden gestionar planificaciones en revisión.');
            }
        }

        $planning->update(['status' => $newStatus]);

        return back()->with('success', 'El estado de la planificación ha sido actualizado.');
    }
}
