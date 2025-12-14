<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($googleDriveConnected)
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-2">Google Drive Conectado</h3>
                    <p class="text-sm text-gray-600 mb-4">Tu cuenta de Google Drive está conectada. Ahora puedes subir planificaciones directamente desde la nube.</p>
                    <a href="{{ route('google.picker') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px"><path fill="#fff" d="M31,36l6-24h-6z"/><path fill="#fff" d="M17,36l-6-24h6z"/></svg>
                        Subir desde Google Drive
                    </a>
                @else
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-2">Conectar con Google Drive</h3>
                    <p class="text-sm text-gray-600 mb-4">Conecta tu cuenta de Google Drive para subir planificaciones directamente desde la nube.</p>
                    <a href="{{ route('google.connect') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px"><path fill="#4285F4" d="M37,12H11l-6,24h38L37,12z"/><path fill="#3F51B5" d="M37,12l-6,24h6l6-24H37z"/><path fill="#4CAF50" d="M11,12l6,24h-6l-6-24H11z"/><path fill="#1E88E5" d="M31,36l6-24h-6z"/><path fill="#2196F3" d="M17,36l-6-24h6z"/></svg>
                        Conectar con Google Drive
                    </a>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Subir Nueva Planificación (desde el ordenador)</h3>
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('plannings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="file" :value="__('Archivo (PDF, DOC, DOCX)')" />
                            <input id="file" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="file" required />
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-3">
                                {{ __('Subir Planificación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Mis Planificaciones</h3>

                        <form action="{{ route('plannings.index') }}" method="GET" class="mb-4">
                            <div class="flex space-x-4">
                                <div class="flex-1">
                                    <x-input-label for="search" :value="__('Buscar por Título')" />
                                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                                </div>
                                <div>
                                    <x-input-label for="status" :value="__('Filtrar por Estado')" />
                                    <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Todos</option>
                                        <option value="borrador" {{ request('status') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                        <option value="revisión" {{ request('status') == 'revisión' ? 'selected' : '' }}>Revisión</option>
                                        <option value="aprobado" {{ request('status') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                        <option value="rechazado" {{ request('status') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <x-primary-button>
                                        {{ __('Buscar') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Título
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha de Subida
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($plannings as $planning)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $planning->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($planning->status)
                                                        @case('borrador') bg-yellow-100 text-yellow-800 @break
                                                        @case('revisión') bg-blue-100 text-blue-800 @break
                                                        @case('aprobado') bg-green-100 text-green-800 @break
                                                        @case('rechazado') bg-red-100 text-red-800 @break
                                                    @endswitch">
                                                    {{ ucfirst($planning->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $planning->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('plannings.download', $planning) }}" class="text-indigo-600 hover:text-indigo-900">Descargar</a>
                                                <a href="{{ route('plannings.view', $planning) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Ver</a>
                                                @if ($planning->status === 'borrador' || $planning->status === 'rechazado')
                                                    <form action="{{ route('plannings.updateStatus', $planning) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="revisión">
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900 ml-4">Enviar a revisión</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No se encontraron planificaciones con los criterios de búsqueda.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $plannings->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
