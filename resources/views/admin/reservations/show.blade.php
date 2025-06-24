@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-check"></i> Detalles de la Reserva #{{ $reservation->id }}</h2>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Información de la Reserva</h5>
                        @if($reservation->status == 'pending')
                            <span class="badge bg-warning fs-6">Pendiente</span>
                        @elseif($reservation->status == 'confirmed')
                            <span class="badge bg-success fs-6">Confirmada</span>
                        @elseif($reservation->status == 'cancelled')
                            <span class="badge bg-danger fs-6">Cancelada</span>
                        @elseif($reservation->status == 'completed')
                            <span class="badge bg-info fs-6">Completada</span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Información de la Reserva -->
                            <div class="col-md-6">
                                <h6><i class="fas fa-info-circle"></i> Detalles de la Reserva</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>#{{ $reservation->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha:</strong></td>
                                        <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Horario:</strong></td>
                                        <td>{{ $reservation->start_time }} - {{ $reservation->end_time }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Duración:</strong></td>
                                        <td>{{ $reservation->getDurationInHours() }} hora(s)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Monto Total:</strong></td>
                                        <td class="h5 text-success">${{ number_format($reservation->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Creada:</strong></td>
                                        <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Información del Usuario -->
                            <div class="col-md-6">
                                <h6><i class="fas fa-user"></i> Información del Cliente</h6>
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
                                    <tr>
                                        <td><strong>Registrado:</strong></td>
                                        <td>{{ $reservation->user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Información de la Cancha -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-futbol"></i> Información de la Cancha</h6>
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
                                    <tr>
                                        <td><strong>Capacidad:</strong></td>
                                        <td>{{ $reservation->court->capacity }} personas</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Precio/hora:</strong></td>
                                        <td>${{ number_format($reservation->court->price_per_hour ?? 50, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Propietario:</strong></td>
                                        <td>{{ $reservation->court->user->name ?? 'No asignado' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <!-- Notas -->
                                @if($reservation->notes)
                                    <h6><i class="fas fa-sticky-note"></i> Notas del Cliente</h6>
                                    <div class="alert alert-info">
                                        {{ $reservation->notes }}
                                    </div>
                                @endif

                                <!-- Reglas de la Cancha -->
                                @if($reservation->court->rules)
                                    <h6><i class="fas fa-list-ul"></i> Reglas de la Cancha</h6>
                                    <div class="alert alert-warning">
                                        {{ $reservation->court->rules }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Acciones Administrativas -->
                        <div class="d-flex justify-content-between">
                            <div>
                                @if($reservation->status == 'pending')
                                    <form method="POST" action="{{ route('admin.reservations.update-status', $reservation) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Confirmar Reserva
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.reservations.update-status', $reservation) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times"></i> Cancelar Reserva
                                        </button>
                                    </form>
                                @elseif($reservation->status == 'confirmed')
                                    <form method="POST" action="{{ route('admin.reservations.update-status', $reservation) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-flag-checkered"></i> Marcar como Completada
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div>
                                <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}"
                                    style="display: inline;"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta reserva? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i> Eliminar Reserva
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection