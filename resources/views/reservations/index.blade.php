@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">
                        <i class="fas fa-calendar me-2"></i>
                        @if(Auth::user()->isAdmin())
                            Gestión de Reservas
                        @else
                            Mis Reservas
                        @endif
                    </h1>
                    <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Nueva Reserva
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reservations.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">Desde</label>
                                    <input type="date" class="form-control" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">Hasta</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-control" name="status">
                                        <option value="">Todos</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            Confirmado</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelado</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Completado</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i>Filtrar
                                    </button>
                                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservations List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if($reservations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            @if(Auth::user()->isAdmin())
                                                <th>Cliente</th>
                                            @endif
                                            <th>Cancha</th>
                                            <th>Fecha</th>
                                            <th>Horario</th>
                                            <th>Estado</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservations as $reservation)
                                            <tr>
                                                @if(Auth::user()->isAdmin())
                                                    <td>
                                                        <strong>{{ $reservation->user->name }}</strong><br>
                                                        <small class="text-muted">{{ $reservation->user->email }}</small>
                                                    </td>
                                                @endif
                                                <td>
                                                    <strong>{{ $reservation->court->name }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ ucfirst($reservation->court->surface_type) }}</small>
                                                </td>
                                                <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                                <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'completed' ? 'info' : 'danger')) }}">
                                                        {{ ucfirst($reservation->status) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($reservation->total_amount, 0) }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('reservations.show', $reservation) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($reservation->status == 'pending' && (Auth::user()->isAdmin() || Auth::user()->id == $reservation->user_id))
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="cancelReservation({{ $reservation->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $reservations->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5>No hay reservas</h5>
                                <p class="text-muted">
                                    @if(Auth::user()->isAdmin())
                                        No se han registrado reservas aún.
                                    @else
                                        No tienes reservas registradas.
                                    @endif
                                </p>
                                <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Nueva Reserva
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cancelReservation(id) {
            if (confirm('¿Estás seguro de que quieres cancelar esta reserva?')) {
                // Crear y enviar formulario para cancelar
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/reservations/${id}`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';

                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = 'cancelled';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection