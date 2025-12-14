<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Planificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card de Detalles de la Planificación -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $planning->title }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <span class="font-bold text-gray-600">Estado:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($planning->status)
                                    @case('borrador') bg-yellow-100 text-yellow-800 @break
                                    @case('revisión') bg-blue-100 text-blue-800 @break
                                    @case('aprobado') bg-green-100 text-green-800 @break
                                    @case('rechazado') bg-red-100 text-red-800 @break
                                @endswitch">
                                {{ ucfirst($planning->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="font-bold text-gray-600">Fecha de Subida:</span>
                            <span>{{ $planning->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <a href="{{ route('plannings.download', $planning) }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                                <svg class="inline-block w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Descargar Archivo
                            </a>
                        </div>
                    </div>

                    <!-- Visor de Documentos Híbrido -->
                    @if($planning->file_path)
                        <div class="mt-6 border-t pt-6">
                            <h4 class="text-xl font-bold text-gray-800 mb-4">Visualizador de Documento</h4>

                            @php
                                $fileExtension = strtolower(pathinfo($planning->file_path, PATHINFO_EXTENSION));
                            @endphp

                            @if($fileExtension === 'pdf')
                                <div class="bg-gray-100 p-2 rounded-lg">
                                    <iframe src="{{ Storage::url($planning->file_path) }}" style="width:100%; height:700px;" frameborder="0"></iframe>
                                </div>
                            @elseif(in_array($fileExtension, ['doc', 'docx']))
                                @if(app()->isProduction())
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <iframe src="https://docs.google.com/gview?url={{ url(Storage::url($planning->file_path)) }}&embedded=true" style="width:100%; height:700px;" frameborder="0"></iframe>
                                    </div>
                                @else
                                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg" role="alert">
                                        <p class="font-bold">Vista previa no disponible para archivos de Word en el entorno de desarrollo.</p>
                                        <p>Por ahora, utiliza el enlace de descarga para revisarlo.</p>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-100 text-gray-600 p-4 rounded-lg">
                                    <p>No hay una vista previa disponible para este tipo de archivo.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-6 text-center text-gray-500">
                            <p>No hay un archivo asociado a esta planificación.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel de Gestión de Estado (Solo para roles autorizados y estado 'revisión') -->
            @auth
                @if( (Auth::user()->hasRole('secretaria') || Auth::user()->hasRole('vicerrector')) && $planning->status === 'revisión' )
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border-t-4 border-blue-500">
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-800 mb-4">Panel de Gestión de Estado</h4>
                            <p class="text-gray-600 mb-6">Como usuario autorizado, puedes cambiar el estado de esta planificación. El docente será notificado del cambio.</p>
                            <div class="flex justify-start space-x-4">
                                <!-- Formulario para Aprobar -->
                                <form action="{{ route('plannings.updateStatus', $planning) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="aprobado">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Aprobar
                                    </button>
                                </form>

                                <!-- Formulario para Rechazar -->
                                <form action="{{ route('plannings.updateStatus', $planning) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rechazado">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Rechazar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Sección de Comentarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-2xl font-bold text-gray-900 mb-6">Comentarios</h4>
                    <div class="mb-8">
                        <form action="{{ route('comments.store', $planning) }}" method="POST">
                            @csrf
                            <div>
                                <label for="body" class="sr-only">Añadir un comentario</label>
                                <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Escribe tu comentario aquí..."></textarea>
                            </div>
                            <div class="mt-4 text-right">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Enviar Comentario
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="space-y-6">
                        @forelse ($planning->comments as $comment)
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</p>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700">{{ $comment->body }}</p>
                                @can('delete', $comment)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="text-right mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                    </form>
                                @endcan
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                <p>Aún no hay comentarios. ¡Sé el primero en comentar!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
