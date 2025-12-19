<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adjuntar Archivo desde Google Drive') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-10 text-gray-900">
                    
                    <div class="text-center" id="picker-container">
                        <p class="mb-4 text-lg">Haz clic para seleccionar un archivo de tu Google Drive y adjuntarlo a una nueva planificación.</p>
                        <button id="picker-button" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Seleccionar Archivo de Drive
                        </button>
                    </div>

                    <div id="result-container" class="mt-8 text-center hidden">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <strong class="font-bold">Archivo Seleccionado:</strong>
                            <span class="block sm:inline ml-2" id="file-name"></span>
                        </div>
                        
                        <form action="{{ route('plannings.store') }}" method="POST" class="mt-6 max-w-lg mx-auto text-left">
                            @csrf
                            <input type="hidden" name="google_drive_file_id" id="google_drive_file_id">
                            
                            <!-- Campo Título -->
                            <div class="mb-4">
                                <x-input-label for="title" :value="__('Título de la Planificación')" class="!text-lg"/>
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" required autofocus placeholder="Ej: Planificación de Matemáticas - Semana 1"/>
                            </div>

                            <!-- Campo Selector de Área Académica -->
                            <div class="mb-6">
                                <x-input-label for="subject_id" :value="__('Área Académica')" class="!text-lg"/>
                                <select name="subject_id" id="subject_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Selecciona un área</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center">
                                <x-primary-button>
                                    {{ __('Crear Planificación con este Archivo') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script async defer src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
    <script type="text/javascript">
        
        const DEVELOPER_KEY = '{{ $developerKey ?? '' }}';
        const APP_ID = '{{ $appId ?? '' }}';
        const OAUTH_TOKEN = '{{ $accessToken ?? '' }}';

        const pickerButton = document.getElementById('picker-button');
        const pickerContainer = document.getElementById('picker-container');
        const resultContainer = document.getElementById('result-container');
        const fileNameElement = document.getElementById('file-name');
        const fileIdInput = document.getElementById('google_drive_file_id');

        let pickerApiLoaded = false;

        window.onApiLoad = function() {
            gapi.load('picker', () => { pickerApiLoaded = true; });
        }

        pickerButton.addEventListener('click', () => {
            if (pickerApiLoaded && OAUTH_TOKEN) {
                createPicker();
            } else {
                alert('Error de autenticación o la API de Google no ha cargado. Por favor, intenta de nuevo.');
            }
        });
        
        function createPicker() {
            const view = new google.picker.View(google.picker.ViewId.DOCUMENTS);
            view.setMimeTypes("application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/msword");

            const picker = new google.picker.PickerBuilder()
                .setAppId(APP_ID)
                .setOAuthToken(OAUTH_TOKEN)
                .setDeveloperKey(DEVELOPER_KEY)
                .addView(view)
                .setCallback(pickerCallback)
                .build();
            
            picker.setVisible(true);
        }

        function pickerCallback(data) {
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                const doc = data[google.picker.Response.DOCUMENTS][0];
                fileNameElement.textContent = doc[google.picker.Document.NAME];
                fileIdInput.value = doc[google.picker.Document.ID];
                pickerContainer.classList.add('hidden');
                resultContainer.classList.remove('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
