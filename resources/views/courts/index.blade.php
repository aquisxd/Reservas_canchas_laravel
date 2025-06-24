@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Administración de Canchas</h1>
                        <p class="text-gray-600 mt-1">Gestiona todas las canchas del sistema</p>
                    </div>
                    <a href="{{ route('courts.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Nueva Cancha
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                <form method="GET" class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, ubicación o propietario..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <select name="surface_type"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las superficies</option>
                            <option value="clay" {{ request('surface_type') == 'clay' ? 'selected' : '' }}>Arcilla</option>
                            <option value="hard" {{ request('surface_type') == 'hard' ? 'selected' : '' }}>Dura</option>
                            <option value="grass" {{ request('surface_type') == 'grass' ? 'selected' : '' }}>Césped</option>
                            <option value="synthetic" {{ request('surface_type') == 'synthetic' ? 'selected' : '' }}>Sintética
                            </option>
                        </select>
                    </div>
                    <div>
                        <select name="status"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activa</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactiva</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                Mantenimiento</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.courts.index') }}" class="text-gray-600 hover:text-gray-800">
                        Limpiar
                    </a>
                </form>
            </div>

            <!-- Tabla de canchas -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cancha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Propietario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio/Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reservas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($courts ?? [] as $court)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($court->image)
                                                <img src="{{ $court->image_url }}" alt="{{ $court->name }}"
                                                    class="h-12 w-12 rounded-lg object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-futbol text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $court->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $court->surface_type_name }}</div>
                                            @if($court->location)
                                                <div class="text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $court->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $court->user->name ?? 'Sin propietario' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $court->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $court->formatted_price }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $court->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>Total: {{ $court->total_reservations }}</div>
                                    <div class="text-green-600">Ingresos: ${{ number_format($court->monthly_revenue, 0) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.courts.show', $court) }}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('courts.edit', $court) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('courts.reservations', $court) }}"
                                        class="text-purple-600 hover:text-purple-900 transition-colors">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.courts.toggle-status', $court) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                            title="Cambiar estado">
                                            <i class="fas fa-toggle-{{ $court->status === 'active' ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>

                                    <button onclick="confirmDelete({{ $court->id }}, '{{ $court->name }}')"
                                        class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No se encontraron canchas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if(isset($courts) && $courts->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($courts->previousPageUrl())
                            <a href="{{ $courts->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </a>
                        @endif
                        @if($courts->nextPageUrl())
                            <a href="{{ $courts->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Siguiente
                            </a>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando {{ $courts->firstItem() }} a {{ $courts->lastItem() }} de {{ $courts->total() }}
                                resultados
                            </p>
                        </div>
                        <div>
                            {{ $courts->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Eliminación</h3>
                <p class="text-sm text-gray-500 mb-4" id="deleteMessage">
                    ¿Estás seguro de que quieres eliminar esta cancha?
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function confirmDelete(courtId, courtName) {
            document.getElementById('deleteMessage').textContent = `¿Estás seguro de que quieres eliminar la cancha "${courtName}"?`;
            document.getElementById('deleteForm').action = `/admin/courts/${courtId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function (event) {
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>
@endpush