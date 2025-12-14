<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seleccionar Archivo de Google Drive') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">Haz clic en el siguiente botón para seleccionar un archivo de tu Google Drive.</p>
                    <button id="picker-button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Seleccionar Archivo
                    </button>

                    <div id="result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Load the Google Picker API
        gapi.load('picker', {'callback': onPickerApiLoad});
        gapi.load('auth', {'callback': onAuthApiLoad});

        let pickerApiLoaded = false;
        let oauthToken;

        function onPickerApiLoad() {
            pickerApiLoaded = true;
            createPicker();
        }

        function onAuthApiLoad() {
            // Do nothing
        }

        function createPicker() {
            if (pickerApiLoaded && oauthToken) {
                const view = new google.picker.View(google.picker.ViewId.DOCS);
                view.setMimeTypes("application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword");

                const picker = new google.picker.PickerBuilder()
                    .setAppId("{{ $clientId }}")
                    .setOAuthToken(oauthToken)
                    .setDeveloperKey("{{ $developerKey }}")
                    .addView(view)
                    .setCallback(pickerCallback)
                    .build();
                picker.setVisible(true);
            }
        }

        function pickerCallback(data) {
            let result = '';
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                const doc = data[google.picker.Response.DOCUMENTS][0];
                const fileId = doc[google.picker.Document.ID];
                const fileName = doc[google.picker.Document.NAME];

                result = `
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <p><strong>Archivo Seleccionado:</strong> ${fileName}</p>
                        <p><strong>ID del Archivo:</strong> ${fileId}</p>
                    </div>
                    <form action="{{ route('plannings.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="google_drive_file_id" value="${fileId}">
                        <input type="hidden" name="title" value="${fileName}">
                        <x-primary-button>{{ __('Crear Planificación a partir de este Archivo') }}</x-primary-button>
                    </form>
                `;
            }
            document.getElementById('result').innerHTML = result;
        }

        document.getElementById('picker-button').addEventListener('click', () => {
            oauthToken = "{{ $accessToken }}";
            createPicker();
        });
    </script>

    <script type="text/javascript" src="https://apis.google.com/js/api.js?onload=onGoogleApiLoad"></script>
</x-app-layout>
