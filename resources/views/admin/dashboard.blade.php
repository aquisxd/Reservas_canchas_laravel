@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Administrativo</h1>
        <p>Bienvenido al panel de administración</p>

        @if(isset($stats))
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Usuarios</h5>
                            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Canchas</h5>
                            <h3>{{ $stats['total_courts'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Reservas</h5>
                            <h3>{{ $stats['total_reservations'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Reservas Hoy</h5>
                            <h3>{{ $stats['today_reservations'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection