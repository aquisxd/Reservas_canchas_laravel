@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Nueva Cancha - FUNCIONANDO ✅</h1>
        <p>Si ves este mensaje, la vista está funcionando correctamente.</p>

        <form action="{{ route('courts.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label>Nombre de la cancha:</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Descripción:</label>
                <textarea name="description" style="width: 100%; padding: 8px;"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Capacidad:</label>
                <select name="capacity" required style="width: 100%; padding: 8px;">
                    <option value="2">2 jugadores</option>
                    <option value="4">4 jugadores</option>
                    <option value="6">6 jugadores</option>
                    <option value="8">8 jugadores</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Hora apertura:</label>
                <input type="time" name="opening_time" value="08:00" required style="padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Hora cierre:</label>
                <input type="time" name="closing_time" value="22:00" required style="padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Estado:</label>
                <select name="status" required style="width: 100%; padding: 8px;">
                    <option value="active">Activa</option>
                    <option value="inactive">Inactiva</option>
                    <option value="maintenance">Mantenimiento</option>
                </select>
            </div>

            <button type="submit"
                style="background: #059669; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                Crear Cancha
            </button>

            <a href="{{ route('courts.index') }}"
                style="margin-left: 10px; padding: 10px 20px; background: #6b7280; color: white; text-decoration: none; border-radius: 5px;">
                Cancelar
            </a>
        </form>
    </div>
@endsection