@extends('layouts.app')

@push('styles')
    <style>
        .court-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .court-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .court-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .court-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .court-content {
            padding: 20px;
        }

        .court-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .court-type {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 12px;
            text-transform: capitalize;
        }

        .court-description {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .court-price {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .btn-primary {
            background: #059669;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary:hover {
            background: #047857;
            color: white;
            text-decoration: none;
        }

        .btn-small {
            background: #6b7280;
            color: white;
            padding: 8px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            min-width: 36px;
            height: 36px;
        }

        .btn-small.btn-blue {
            background: #2563eb;
        }

        .btn-small.btn-blue:hover {
            background: #1d4ed8;
        }

        .btn-small.btn-yellow {
            background: #d97706;
        }

        .btn-small.btn-yellow:hover {
            background: #b45309;
        }

        .btn-small.btn-red {
            background: #dc2626;
        }

        .btn-small.btn-red:hover {
            background: #b91c1c;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-maintenance {
            background: #fef3c7;
            color: #92400e;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .filter-container {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .filters-row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 16px;
            align-items: end;
        }

        .actions-group {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .court-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f3f4f6;
        }

        .location-text {
            font-size: 0.75rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @media (max-width: 768px) {
            .filters-row {
                grid-template-columns: 1fr;
            }

            .grid-container {
                grid-template-columns: 1fr;
            }

            .actions-group {
                flex-direction: column;
            }

            .btn-primary {
                margin-bottom: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div style="min-height: 100vh; background: #f9fafb;">
        <!-- Header -->
        <div style="background: white; border-bottom: 1px solid #e5e7eb;">
            <div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 700; color: #111827; margin: 0;">Canchas Deportivas</h1>
                    </div>
                    <a href="{{ route('courts.create') }}" class="btn-primary" style="width: auto; padding: 12px 20px;">
                        <i class="fas fa-plus" style="margin-right: 8px;"></i>
                        Agregar Nueva Cancha
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">

            <!-- Filtros -->
            <div class="filter-container">
                <form method="GET">
                    <div class="filters-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar canchas..."
                                class="form-input">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Tipo de cancha</label>
                            <select name="surface_type" class="form-input">
                                <option value="">Todas</option>
                                <option value="futbol" {{ request('surface_type') == 'futbol' ? 'selected' : '' }}>Fútbol
                                </option>
                                <option value="tenis" {{ request('surface_type') == 'tenis' ? 'selected' : '' }}>Tenis
                                </option>
                                <option value="basquet" {{ request('surface_type') == 'basquet' ? 'selected' : '' }}>Básquet
                                </option>
                                <option value="voley" {{ request('surface_type') == 'voley' ? 'selected' : '' }}>Vóley
                                </option>
                                <option value="padel" {{ request('surface_type') == 'padel' ? 'selected' : '' }}>Pádel
                                </option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn-primary" style="width: auto; padding: 10px 20px;">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Grid de canchas -->
            <div class="grid-container">
                @forelse($courts ?? [] as $court)
                    <div class="court-card">
                        <!-- Imagen -->
                        <div class="court-image">
                            @if($court->image)
                                <img src="{{ $court->image_url }}" alt="{{ $court->name }}">
                            @else
                                @if(str_contains(strtolower($court->name), 'futbol') || str_contains(strtolower($court->name), 'fútbol'))
                                    <i class="fas fa-futbol" style="color: white; font-size: 3rem;"></i>
                                @elseif(str_contains(strtolower($court->name), 'tenis'))
                                    <i class="fas fa-table-tennis" style="color: white; font-size: 3rem;"></i>
                                @elseif(str_contains(strtolower($court->name), 'basquet') || str_contains(strtolower($court->name), 'básquet'))
                                    <i class="fas fa-basketball-ball" style="color: white; font-size: 3rem;"></i>
                                @elseif(str_contains(strtolower($court->name), 'voley') || str_contains(strtolower($court->name), 'vóley'))
                                    <i class="fas fa-volleyball-ball" style="color: white; font-size: 3rem;"></i>
                                @elseif(str_contains(strtolower($court->name), 'padel') || str_contains(strtolower($court->name), 'pádel'))
                                    <i class="fas fa-table-tennis" style="color: white; font-size: 3rem;"></i>
                                @else
                                    <i class="fas fa-running" style="color: white; font-size: 3rem;"></i>
                                @endif
                            @endif
                        </div>

                        <!-- Contenido -->
                        <div class="court-content">
                            <h3 class="court-title">{{ $court->name }}</h3>
                            <p class="court-type">
                                @if(str_contains(strtolower($court->name), 'futbol') || str_contains(strtolower($court->name), 'fútbol'))
                                    Fútbol
                                @elseif(str_contains(strtolower($court->name), 'tenis'))
                                    Tenis
                                @elseif(str_contains(strtolower($court->name), 'basquet') || str_contains(strtolower($court->name), 'básquet'))
                                    Básquet
                                @elseif(str_contains(strtolower($court->name), 'voley') || str_contains(strtolower($court->name), 'vóley'))
                                    Vóley
                                @elseif(str_contains(strtolower($court->name), 'padel') || str_contains(strtolower($court->name), 'pádel'))
                                    Pádel
                                @else
                                    Deportes
                                @endif
                            </p>

                            <p class="court-description">
                                {{ $court->description ?? 'Cancha deportiva profesional. Ideal para partidos y entrenamientos.' }}
                            </p>

                            <p class="court-price">
                                ${{ number_format($court->price ?? 50, 0) }}.00 / hora
                            </p>

                            <!-- Botón principal -->
                            <a href="{{ route('admin.courts.show', $court) }}" class="btn-primary">
                                Administrar
                            </a>

                            <!-- Acciones adicionales -->
                            <div class="actions-group">
                                <a href="{{ route('courts.edit', $court) }}" class="btn-small btn-blue" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.courts.toggle-status', $court) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-small btn-yellow" title="Cambiar estado">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>

                                <button onclick="confirmDelete({{ $court->id }}, '{{ $court->name }}')"
                                    class="btn-small btn-red" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Meta información -->
                            <div class="court-meta">
                                <div>
                                    @if($court->status === 'active')
                                        <span class="status-badge status-active">
                                            <span
                                                style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; margin-right: 6px;"></span>
                                            Activa
                                        </span>
                                    @elseif($court->status === 'inactive')
                                        <span class="status-badge status-inactive">
                                            <span
                                                style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%; margin-right: 6px;"></span>
                                            Inactiva
                                        </span>
                                    @else
                                        <span class="status-badge status-maintenance">
                                            <span
                                                style="width: 6px; height: 6px; background: #f59e0b; border-radius: 50%; margin-right: 6px;"></span>
                                            Mantenimiento
                                        </span>
                                    @endif
                                </div>

                                @if($court->location)
                                    <div class="location-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ Str::limit($court->location, 20) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1;">
                        <div
                            style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 48px; text-align: center;">
                            <div style="margin-bottom: 24px;">
                                <i class="fas fa-search" style="color: #d1d5db; font-size: 4rem;"></i>
                            </div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 8px;">No se
                                encontraron canchas</h3>
                            <p style="color: #6b7280; margin-bottom: 24px;">Intenta ajustar los filtros o agrega una nueva
                                cancha al sistema.</p>
                            <a href="{{ route('courts.create') }}" class="btn-primary" style="width: auto; padding: 12px 20px;">
                                <i class="fas fa-plus" style="margin-right: 8px;"></i>
                                Agregar Primera Cancha
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if(isset($courts) && $courts->hasPages())
                <div style="margin-top: 32px;">
                    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                            <div style="font-size: 0.875rem; color: #6b7280;">
                                Mostrando {{ $courts->firstItem() }} a {{ $courts->lastItem() }} de {{ $courts->total() }}
                                resultados
                            </div>
                            <div>
                                {{ $courts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="deleteModal"
        style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; z-index: 50; align-items: center; justify-content: center; padding: 16px;">
        <div
            style="background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 100%; max-width: 400px; padding: 24px;">
            <div style="text-align: center;">
                <div
                    style="width: 48px; height: 48px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                </div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">Confirmar Eliminación
                </h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 24px;" id="deleteMessage">
                    ¿Estás seguro de que quieres eliminar esta cancha?
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div style="display: flex; gap: 12px;">
                        <button type="button" onclick="closeDeleteModal()"
                            style="flex: 1; padding: 10px 16px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">
                            Cancelar
                        </button>
                        <button type="submit"
                            style="flex: 1; padding: 10px 16px; background: #dc2626; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">
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
            document.getElementById('deleteMessage').textContent = `¿Estás seguro de que quieres eliminar la cancha "${courtName}"? Esta acción no se puede deshacer.`;
            document.getElementById('deleteForm').action = `/admin/courts/${courtId}`;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.onclick = function (event) {
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>
@endpush