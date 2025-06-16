@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marker-alt me-2"></i>Gestión de Canchas
            </h1>
            <p class="mb-0 text-muted">Administra todas las canchas del sistema</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('courts.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i>Nueva Cancha
            </a>
        @endif
    </div>

    <!-- Filter Cards -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('courts.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="surface_type" class="form-label font-weight-bold">Tipo de Superficie</label>
                                <select class="form-control" name="surface_type" id="surface_type">
                                    <option value="">Todas las superficies</option>
                                    <option value="clay" {{ request('surface_type') == 'clay' ? 'selected' : '' }}>🟤
                                        Arcilla</option>
                                    <option value="hard" {{ request('surface_type') == 'hard' ? 'selected' : '' }}>🔵 Dura
                                    </option>
                                    <option value="grass" {{ request('surface_type') == 'grass' ? 'selected' : '' }}>🟢
                                        Césped</option>
                                    <option value="synthetic" {{ request('surface_type') == 'synthetic' ? 'selected' : '' }}>🟡 Sintética</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="price_range" class="form-label font-weight-bold">Rango de Precio</label>
                                <select class="form-control" name="price_range" id="price_range">
                                    <option value="">Todos los precios</option>
                                    <option value="0-30" {{ request('price_range') == '0-30' ? 'selected' : '' }}>$0 - $30
                                    </option>
                                    <option value="30-50" {{ request('price_range') == '30-50' ? 'selected' : '' }}>$30 -
                                        $50</option>
                                    <option value="50+" {{ request('price_range') == '50+' ? 'selected' : '' }}>$50+
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label font-weight-bold">Estado</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Activas
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌
                                        Inactivas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filtrar
                                    </button>
                                    <a href="{{ route('courts.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Courts Grid -->
    <div class="row">
        @forelse($courts as $court)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card shadow h-100 border-0">
                    <!-- Court Image -->
                    <div class="court-image-container" style="height: 200px; position: relative; overflow: hidden;">
                        @if($court->image)
                            <img src="{{ asset('storage/' . $court->image) }}" class="card-img-top" alt="{{ $court->name }}"
                                style="height: 100%; width: 100%; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-tennis-ball fa-3x text-white opacity-75"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="position-absolute" style="top: 10px; right: 10px;">
                            @if($court->is_active)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle me-1"></i>Activa
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle me-1"></i>Inactiva
                                </span>
                            @endif
                        </div>

                        <!-- Surface Type Badge -->
                        <div class="position-absolute" style="top: 10px; left: 10px;">
                            @php
                                $surfaceIcons = [
                                    'clay' => '🟤',
                                    'hard' => '🔵',
                                    'grass' => '🟢',
                                    'synthetic' => '🟡'
                                ];
                                $surfaceNames = [
                                    'clay' => 'Arcilla',
                                    'hard' => 'Dura',
                                    'grass' => 'Césped',
                                    'synthetic' => 'Sintética'
                                ];
                            @endphp
                            <span class="badge badge-light badge-lg">
                                {{ $surfaceIcons[$court->surface_type] ?? '⚪' }}
                                {{ $surfaceNames[$court->surface_type] ?? ucfirst($court->surface_type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column">
                        <div class="flex-grow-1">
                            <h5 class="card-title font-weight-bold mb-2">{{ $court->name }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($court->description, 100, '...') ?: 'Cancha de tenis profesional con excelentes instalaciones.' }}
                            </p>
                        </div>

                        <!-- Price and Info -->
                        <div class="mt-auto">
                            <div class="row align-items-center mb-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h4 font-weight-bold text-success mb-0">
                                            ${{ number_format($court->price_per_hour, 0) }}
                                        </div>
                                        <small class="text-muted">por hora</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h6 font-weight-bold text-info mb-0">
                                            {{ $court->reservations_count ?? 0 }}
                                        </div>
                                        <small class="text-muted">reservas</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('courts.show', $court) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($court->is_active)
                                    <a href="{{ route('reservations.create', ['court_id' => $court->id]) }}"
                                        class="btn btn-success btn-sm flex-grow-1">
                                        <i class="fas fa-calendar-plus me-1"></i>Reservar
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm flex-grow-1" disabled>
                                        <i class="fas fa-ban me-1"></i>No Disponible
                                    </button>
                                @endif

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('courts.edit', $court) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="deleteCourt({{ $court->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer with additional info -->
                    <div class="card-footer bg-transparent border-0">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-star text-warning"></i>
                                    4.8
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-users text-info"></i>
                                    2-4 jugadores
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-clock text-primary"></i>
                                    24/7
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-4"></i>
                        <h4 class="text-muted">No se encontraron canchas</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['surface_type', 'price_range', 'status']))
                                No hay canchas que coincidan con los filtros seleccionados.
                                <br><a href="{{ route('courts.index') }}" class="text-primary">Limpiar filtros</a>
                            @else
                                Aún no se han registrado canchas en el sistema.
                            @endif
                        </p>
                        @if(Auth::user()->isAdmin() && !request()->hasAny(['surface_type', 'price_range', 'status']))
                            <a href="{{ route('courts.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Crear Primera Cancha
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courts->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $courts->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .badge-lg {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    .court-image-container {
        border-radius: 0.35rem 0.35rem 0 0;
    }

    .btn-group .btn:not(:last-child) {
        border-right: 1px solid rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    function deleteCourt(courtId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta cancha? Esta acción no se puede deshacer.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/courts/${courtId}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        # Dashboard y Vistas de Canchas Mejoradas