<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar Reportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="report-form" action="{{ route('reports.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Fecha de Inicio -->
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha de Inicio')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date')" />
                            </div>

                            <!-- Fecha de Fin -->
                            <div>
                                <x-input-label for="end_date" :value="__('Fecha de Fin')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date')" />
                            </div>

                            <!-- Filtro por Docente -->
                            <div>
                                <x-input-label for="teacher_id" :value="__('Docente')" />
                                <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por Área Académica -->
                            <div>
                                <x-input-label for="subject_id" :value="__('Área Académica')" />
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por Estado -->
                            <div>
                                <x-input-label for="status" :value="__('Estado')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todos</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button type="submit">
                                {{ __('Generar Reporte') }}
                            </x-primary-button>
                            <x-secondary-button id="download-pdf-btn">
                                {{ __('Descargar PDF') }}
                            </x-secondary-button>
                            <x-secondary-button id="download-word-btn">
                                {{ __('Descargar Word') }}
                            </x-secondary-button>
                        </div>
                    </form>

                    @if(isset($reportData))
                        <div class="mt-8">
                            <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">
                                Resultados del Reporte
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                                            <th scope="col" "class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área Académica</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($reportData as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->user->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->subject->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($item->status) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    No se encontraron resultados para los filtros seleccionados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('download-pdf-btn').addEventListener('click', function() {
            const form = document.getElementById('report-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.location.href = `{{ route('reports.download', 'pdf') }}?${params}`;
        });

        document.getElementById('download-word-btn').addEventListener('click', function() {
            const form = document.getElementById('report-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.location.href = `{{ route('reports.download', 'word') }}?${params}`;
        });
    </script>
</x-app-layout>
