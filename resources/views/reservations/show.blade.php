@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check"></i> Detalles de la Reserva
                    </h4>
                    <span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <!-- Información de la Reserva -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle"></i> Información de la Reserva</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID de Reserva:</strong></td>
                                    <td>#{{ $reservation->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha:</strong></td>
                                    <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hora:</strong></td>
                                    <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                            @switch($reservation->status)
                                                @case('pending')
                                                    Pendiente
                                                    @break
                                                @case('confirmed')
                                                    Confirmada
                                                    @break
                                                @case('cancelled')
                                                    Cancelada
                                                    @break
                                                @case('completed')
                                                    Completada
                                                    @break
                                                @default
                                                    {{ ucfirst($reservation->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Monto Total:</strong></td>
                                    <td class="h5 text-success">${{ number_format($reservation->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5><i class="fas fa-futbol"></i> Información de la Cancha</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Cancha:</strong></td>
                                    <td>{{ $reservation->court->name }}</td>
                                </tr>
                                @if($reservation->court->location)
                                <tr>
                                    <td><strong>Ubicación:</strong></td>
                                    <td>{{ $reservation->court->location }}</td>
                                </tr>
                                @endif
                                @if($reservation->court->phone)
                                <tr>
                                    <td><strong>Teléfono:</strong></td>
                                    <td>{{ $reservation->court->phone }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Capacidad:</strong></td>
                                    <td>{{ $reservation->court->capacity }} personas</td>
                                </tr>
                                <tr>
                                    <td><strong>Horario:</strong></td>
                                    <td>{{ $reservation->court->opening_time }} - {{ $reservation->court->closing_time }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Notas -->
                    @if($reservation->notes)
                    <div class="mb-4">
                        <h5><i class="fas fa-sticky-note"></i> Notas Adicionales</h5>
                        <div class="alert alert-info">
                            {{ $reservation->notes }}
                        </div>
                    </div>
                    @endif

                    <!-- Reglas de la Cancha -->
                    @if($reservation->court->rules)
                    <div class="mb-4">
                        <h5><i class="fas fa-list-ul"></i> Reglas de la Cancha</h5>
                        <div class="alert alert-warning">
                            {{ $reservation->court->rules }}
                        </div>
                    </div>
                    @endif

                    <!-- Información del Usuario -->
                    <div class="mb-4">
                        <h5><i class="fas fa-user"></i> Información del Cliente</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td>{{ $reservation->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $reservation->user->email }}</td>
                            </tr>
                            @if($reservation->user->phone)
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>{{ $reservation->user->phone }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <!-- Fechas del Sistema -->
                    <div class="mb-4">
                        <small class="text-muted">
                            <strong>Creada:</strong> {{ $reservation->created_at->format('d/m/Y H:i') }} |
                            <strong>Última actualización:</strong> {{ $reservation->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <!-- Acciones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Mis Reservas
                        </a>

                        <div>
                            @if($reservation->status == 'pending' && $reservation->user_id == auth()->id())
                                <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}"
                                      style="display: inline;"
                                      onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Cancelar Reserva
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nueva Reserva
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
