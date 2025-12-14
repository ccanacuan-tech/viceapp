<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrar Planificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('plannings.admin-index') }}" method="GET" class="mb-4">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <x-input-label for="search" :value="__('Buscar por Título o Docente')" />
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Subida</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($plannings as $planning)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $planning->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $planning->title }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $planning->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('plannings.download', $planning) }}" class="text-indigo-600 hover:text-indigo-900">Descargar</a>
                                            <a href="{{ route('plannings.view', $planning) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Ver</a>
                                            @if($planning->status === 'revisión')
                                                <form action="{{ route('plannings.updateStatus', $planning) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="aprobado">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 ml-4">Aprobar</button>
                                                </form>
                                                <form action="{{ route('plannings.updateStatus', $planning) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rechazado">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Rechazar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
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
</x-app-layout>
