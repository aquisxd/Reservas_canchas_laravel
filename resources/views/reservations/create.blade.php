@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Nueva Reserva
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="court_id" class="form-label">Cancha *</label>
                            <select class="form-control @error('court_id') is-invalid @enderror"
                                    id="court_id" name="court_id" required onchange="updatePrice()">
                                <option value="">Seleccionar cancha...</option>
                                @foreach($courts as $courtOption)
                                    <option value="{{ $courtOption->id }}"
                                            data-price="{{ $courtOption->price_per_hour }}"
                                            {{ (old('court_id', $court->id ?? '') == $courtOption->id) ? 'selected' : '' }}>
                                        {{ $courtOption->name }} - {{ ucfirst($courtOption->surface_type) }}
                                        ({{ number_format($courtOption->price_per_hour, 0) }}/hora)
                                    </option>
                                @endforeach
                            </select>
                            @error('court_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="reservation_date" class="form-label">Fecha *</label>
                                    <input type="date"
                                           class="form-control @error('reservation_date') is-invalid @enderror"
                                           id="reservation_date" name="reservation_date"
                                           value="{{ old('reservation_date', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}" required onchange="calculateTotal()">
                                    @error('reservation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="start_time" class="form-label">Hora Inicio *</label>
                                    <input type="time"
                                           class="form-control @error('start_time') is-invalid @enderror"
                                           id="start_time" name="start_time"
                                           value="{{ old('start_time', '08:00') }}"
                                           required onchange="calculateTotal()">
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="end_time" class="form-label">Hora Fin *</label>
                                    <input type="time"
                                           class="form-control @error('end_time') is-invalid @enderror"
                                           id="end_time" name="end_time"
                                           value="{{ old('end_time', '09:00') }}"
                                           required onchange="calculateTotal()">
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Información adicional...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Calculation -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="mb-1">Resumen de la Reserva</h5>
                                        <p class="mb-0 text-muted">
                                            <span id="duration-text">Duración: 1 hora</span><br>
                                            <span id="price-text">Precio por hora: $0</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <h3 class="text-primary mb-0">
                                            Total: $<span id="total-amount">0</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-1"></i>Crear Reserva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePrice() {
    calculateTotal();
}

function calculateTotal() {
    const courtSelect = document.getElementById('court_id');
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;

    if (courtSelect.value && startTime && endTime) {
        const selectedOption = courtSelect.options[courtSelect.selectedIndex];
        const pricePerHour = parseFloat(selectedOption.dataset.price) || 0;

        const start = new Date('2000-01-01 ' + startTime);
        const end = new Date('2000-01-01 ' + endTime);
        const diff = (end - start) / (1000 * 60 * 60); // difference in hours

        if (diff > 0) {
            const total = pricePerHour * diff;
            document.getElementById('total-amount').textContent = Math.round(total);
            document.getElementById('duration-text').textContent = `Duración: ${diff} hora${diff !== 1 ? 's' : ''}`;
            document.getElementById('price-text').textContent = `Precio por hora: ${Math.round(pricePerHour)}`;
        }
    }
}

// Calculate on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection
