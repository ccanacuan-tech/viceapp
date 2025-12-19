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
                            <x-secondary-button id="view-word-btn">
                                {{ __('Visualizar Word') }}
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

    <!-- Word Viewer Modal -->
    <div id="word-viewer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <p class="text-2xl font-bold">Vista Previa del Reporte</p>
                <div id="close-modal-btn" class="cursor-pointer z-50">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M18.3 5.71a.996.996 0 00-1.41 0L12 10.59 7.11 5.7A.996.996 0 105.7 7.11L10.59 12 5.7 16.89a.996.996 0 101.41 1.41L12 13.41l4.89 4.89a.996.996 0 101.41-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z"/></svg>
                </div>
            </div>
            <div id="word-content" class="prose max-w-none"></div>
             <div id="loading-indicator" class="text-center p-8 hidden">
                <p class="text-lg font-semibold">Cargando vista previa...</p>
                <p class="text-gray-500">Esto puede tardar unos segundos.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
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

        // Word Viewer Logic
        const viewWordBtn = document.getElementById('view-word-btn');
        const modal = document.getElementById('word-viewer-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const wordContent = document.getElementById('word-content');
        const loadingIndicator = document.getElementById('loading-indicator');

        viewWordBtn.addEventListener('click', async function() {
            modal.classList.remove('hidden');
            wordContent.innerHTML = ''; // Clear previous content
            loadingIndicator.classList.remove('hidden');

            const form = document.getElementById('report-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            const url = `{{ route('reports.download', 'word') }}?${params}`;

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Error al generar el reporte.');
                }
                const arrayBuffer = await response.arrayBuffer();
                const result = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
                wordContent.innerHTML = result.value;
            } catch (error) {
                wordContent.innerHTML = `<p class="text-red-500">${error.message}</p>`;
                console.error('Error al visualizar el Word:', error);
            } finally {
                loadingIndicator.classList.add('hidden');
            }
        });

        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Close modal on escape key press
        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                modal.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
