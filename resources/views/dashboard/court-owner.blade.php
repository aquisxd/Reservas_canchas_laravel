@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header del Dashboard -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Panel de Propietario</h1>
            <p class="text-gray-600 mt-2">Gestiona tus canchas y reservas</p>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Mis Canchas -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Mis Canchas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $myCourtCount }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-futbol text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Reservas Hoy -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Reservas Hoy</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayReservations }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Ingresos del Mes -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ingresos del Mes</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Reservas Pendientes -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingReservations }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-clock text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @can('create courts')
                        <a href="{{ route('courts.create') }}"
                            class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-200">
                            <i class="fas fa-plus text-blue-600 mr-3"></i>
                            <span class="text-blue-700 font-medium">Nueva Cancha</span>
                        </a>
                    @endcan

                    <a href="{{ route('courts.index') }}"
                        class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border border-green-200">
                        <i class="fas fa-list text-green-600 mr-3"></i>
                        <span class="text-green-700 font-medium">Ver Mis Canchas</span>
                    </a>

                    <a href="{{ route('reservations.index') }}"
                        class="flex items-center justify-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors border border-yellow-200">
                        <i class="fas fa-calendar-alt text-yellow-600 mr-3"></i>
                        <span class="text-yellow-700 font-medium">Ver Reservas</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Grid de contenido principal -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Próximas Reservas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Próximas Reservas</h3>
                </div>
                <div class="p-6">
                    @if($upcomingReservations->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingReservations as $reservation)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $reservation->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $reservation->court->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $reservation->reservation_date->format('d/m/Y') }} -
                                            {{ $reservation->start_time }} a {{ $reservation->end_time }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Confirmada</span>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            ${{ number_format($reservation->total_price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800">
                                Ver todas las reservas →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No tienes próximas reservas</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mis Canchas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Mis Canchas</h3>
                </div>
                <div class="p-6">
                    @if($myCourts->count() > 0)
                        <div class="space-y-4">
                            @foreach($myCourts as $court)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($court->image)
                                            <img src="{{ asset('storage/' . $court->image) }}" alt="{{ $court->name }}"
                                                class="w-12 h-12 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-futbol text-gray-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $court->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $court->sport_type }}</p>
                                            <p class="text-xs text-gray-500">${{ number_format($court->price_per_hour, 2) }}/hora
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($court->status === 'active')
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Activa</span>
                                        @elseif($court->status === 'maintenance')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Mantenimiento</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Inactiva</span>
                                        @endif
                                        <div class="mt-2 space-x-2">
                                            <a href="{{ route('courts.show', $court) }}"
                                                class="text-blue-600 hover:text-blue-800 text-xs">
                                                Ver
                                            </a>
                                            @can('edit own courts')
                                                <a href="{{ route('courts.edit', $court) }}"
                                                    class="text-indigo-600 hover:text-indigo-800 text-xs">
                                                    Editar
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('courts.index') }}" class="text-blue-600 hover:text-blue-800">
                                Ver todas mis canchas →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-futbol text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500 mb-4">No tienes canchas registradas</p>
                            @can('create courts')
                                <a href="{{ route('courts.create') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Crear mi primera cancha
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reservas Recientes -->
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
            </div>
            <div class="p-6">
                @if($recentReservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentReservations as $reservation)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-700 font-medium text-sm">
                                                {{ strtoupper(substr($reservation->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-900">{{ $reservation->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $reservation->court->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $reservation->reservation_date->format('d/m/Y') }} -
                                            {{ $reservation->start_time }} a {{ $reservation->end_time }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($reservation->status === 'confirmed')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Confirmada</span>
                                    @elseif($reservation->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Pendiente</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Cancelada</span>
                                    @endif
                                    <p class="text-sm font-medium text-gray-900 mt-1">
                                        ${{ number_format($reservation->total_price, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ $reservation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No hay actividad reciente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection