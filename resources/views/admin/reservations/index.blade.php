@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gestión de Reservas</h3>
                    </div>

                    <!-- Estadísticas -->
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Reservas</span>
                                        <span class="info-box-number">{{ $stats['total_reservations'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pendientes</span>
                                        <span class="info-box-number">{{ $stats['pending_reservations'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Confirmadas</span>
                                        <span class="info-box-number">{{ $stats['confirmed_reservations'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Ingresos Mes</span>
                                        <span
                                            class="info-box-number">${{ number_format($stats['monthly_revenue'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <form method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Buscar usuario o cancha..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            Confirmada</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelada</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Completada</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="court_id" class="form-control">
                                        <option value="">Todas las canchas</option>
                                        @foreach($courts as $court)
                                            <option value="{{ $court->id }}" {{ request('court_id') == $court->id ? 'selected' : '' }}>
                                                {{ $court->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <!-- Tabla de reservas -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Cancha</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservations as $reservation)
                                        <tr>
                                            <td>{{ $reservation->id }}</td>
                                            <td>{{ $reservation->user->name }}</td>
                                            <td>{{ $reservation->court->name }}</td>
                                            <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                            <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                            <td>${{ number_format($reservation->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($reservation->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.reservations.show', $reservation) }}"
                                                    class="btn btn-sm btn-info">Ver</a>

                                                @if($reservation->status == 'pending')
                                                    <form method="POST"
                                                        action="{{ route('admin.reservations.update-status', $reservation) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                                    </form>

                                                    <form method="POST"
                                                        action="{{ route('admin.reservations.update-status', $reservation) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                                    </form>
                                                @endif

                                                <form method="POST"
                                                    action="{{ route('admin.reservations.destroy', $reservation) }}"
                                                    style="display: inline;" onsubmit="return confirm('¿Está seguro?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No se encontraron reservas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
