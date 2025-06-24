@extends('layouts.app')

@push('styles')
    <style>
        .reservation-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 32px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            background: white;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-control:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: #059669;
            color: white;
        }

        .btn-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
            margin-top: 16px;
        }

        .time-slot {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .time-slot:hover {
            border-color: #059669;
            background: #f0fdf4;
        }

        .time-slot.selected {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .time-slot.unavailable {
            background: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
            cursor: not-allowed;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        .court-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .court-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .court-details {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .price-info {
            background: #fef7cd;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }

        .price-text {
            font-size: 1.125rem;
            font-weight: 600;
            color: #92400e;
        }

        @media (max-width: 768px) {
            .reservation-form {
                padding: 20px;
            }

            .availability-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
        }
    </style>
@endpush

@section('content')
    <div style="min-height: 100vh; background: #f1f5f9; padding: 24px 0;">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 16px;">

            <!-- Header -->
            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
                    Nueva Reserva
                </h1>
                <p style="color: #64748b; font-size: 1.125rem;">
                    Reserva tu cancha deportiva favorita
                </p>
            </div>

            <!-- Errores -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>¡Ups! Algo salió mal:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario de Reserva -->
            <form method="POST" action="{{ route('reservations.store') }}" id="reservationForm" class="reservation-form">
                @csrf

                <!-- Selección de Cancha -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-futbol" style="margin-right: 8px;"></i>
                        Cancha Deportiva
                    </label>
                    <select name="court_id" id="courtSelect" class="form-control" required>
                        <option value="">Selecciona una cancha...</option>
                        @foreach($courts as $courtOption)
                            <option value="{{ $courtOption->id }}" data-price="{{ $courtOption->price_per_hour ?? 50 }}"
                                data-opening="{{ $courtOption->opening_time }}" data-closing="{{ $courtOption->closing_time }}"
                                {{ (isset($court) && $court->id == $courtOption->id) || old('court_id') == $courtOption->id ? 'selected' : '' }}>
                                {{ $courtOption->name }} - ${{ number_format($courtOption->price_per_hour ?? 50, 0) }}/hora
                                @if($courtOption->location)
                                    ({{ $courtOption->location }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Información de la Cancha -->
                <div id="courtInfo" class="court-info" style="display: none;">
                    <div class="court-title" id="courtName"></div>
                    <div class="court-details" id="courtDetails"></div>
                    <div class="price-info">
                        <div class="price-text">Precio: $<span id="courtPrice">50</span> por hora</div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar" style="margin-right: 8px;"></i>
                        Fecha de la Reserva
                    </label>
                    <input type="date" name="reservation_date" id="reservationDate" class="form-control" min="{{ $today }}"
                        max="{{ $maxDate }}" value="{{ old('reservation_date') }}" required>
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 4px; display: block;">
                        Puedes reservar hasta 3 meses de anticipación
                    </small>
                </div>

                <!-- Horarios Disponibles -->
                <div class="form-group" id="timeSection" style="display: none;">
                    <label class="form-label">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>
                        Horarios Disponibles
                    </label>
                    <div class="loading" id="loadingTimes">
                        <i class="fas fa-spinner fa-spin"></i> Cargando horarios disponibles...
                    </div>
                    <div id="availabilityGrid" class="availability-grid"></div>
                </div>

                <!-- Horarios seleccionados (campos ocultos para el formulario) -->
                <input type="hidden" name="start_time" id="startTime" value="{{ old('start_time') }}">
                <input type="hidden" name="end_time" id="endTime" value="{{ old('end_time') }}">
                <input type="hidden" name="total_amount" id="totalAmount" value="{{ old('total_amount') }}">

                <!-- Selección Manual de Horario (SIEMPRE VISIBLE después de seleccionar fecha) -->
                <div class="form-group" id="manualTimeSection">
                    <label class="form-label">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>
                        Seleccionar Horario
                    </label>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; margin-bottom: 4px;">
                                Hora de Inicio *
                            </label>
                            <input type="time" id="manualStartTime" class="form-control" value="{{ old('start_time') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size: 0.75rem; margin-bottom: 4px;">
                                Hora de Fin *
                            </label>
                            <input type="time" id="manualEndTime" class="form-control" value="{{ old('end_time') }}"
                                required>
                        </div>
                    </div>
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 8px; display: block;">
                        💡 <strong>Tip:</strong> Al seleccionar la hora de inicio, se calculará automáticamente la hora de
                        fin (1 hora después)
                    </small>
                </div>

                <!-- Notas Adicionales -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sticky-note" style="margin-right: 8px;"></i>
                        Notas Adicionales (Opcional)
                    </label>
                    <textarea name="notes" class="form-control" rows="3"
                        placeholder="Escribe cualquier información adicional sobre tu reserva...">{{ old('notes') }}</textarea>
                </div>

                <!-- Resumen de la Reserva -->
                <div id="reservationSummary" class="court-info" style="display: none;">
                    <h3 style="margin-bottom: 16px; color: #1e293b;">📋 Resumen de tu Reserva</h3>
                    <div style="display: grid; gap: 8px;">
                        <div><strong>Cancha:</strong> <span id="summaryCourtName">-</span></div>
                        <div><strong>Fecha:</strong> <span id="summaryDate">-</span></div>
                        <div><strong>Horario:</strong> <span id="summaryTime">-</span></div>
                        <div><strong>Duración:</strong> <span id="summaryDuration">-</span></div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: #059669; margin-top: 8px;">
                            <strong>Total: $<span id="summaryTotal">0</span></strong>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: 16px; justify-content: center; margin-top: 32px;">
                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled style="opacity: 0.6;">
                        <i class="fas fa-check"></i>
                        Confirmar Reserva
                    </button>
                </div>

                <!-- Debug info (puedes eliminar esto después) -->
                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; font-size: 0.875rem; color: #666;"
                    id="debugInfo">
                    <strong>Info de Debug:</strong><br>
                    <div id="debugContent">Selecciona una cancha y fecha para ver la información</div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courtSelect = document.getElementById('courtSelect');
            const dateInput = document.getElementById('reservationDate');
            const timeSection = document.getElementById('timeSection');
            const courtInfo = document.getElementById('courtInfo');
            const submitBtn = document.getElementById('submitBtn');
            const reservationSummary = document.getElementById('reservationSummary');

            let selectedTimeSlot = null;

            // Cuando se selecciona una cancha
            courtSelect.addEventListener('change', function () {
                if (this.value) {
                    showCourtInfo();
                    if (dateInput.value) {
                        loadAvailableTimes();
                    }
                } else {
                    hideCourtInfo();
                }
            });

            // Cuando se selecciona una fecha
            dateInput.addEventListener('change', function () {
                if (courtSelect.value && this.value) {
                    loadAvailableTimes();
                }
            });

            function showCourtInfo() {
                const selectedOption = courtSelect.options[courtSelect.selectedIndex];
                const courtName = selectedOption.text.split(' - ')[0];
                const price = selectedOption.dataset.price;
                const opening = selectedOption.dataset.opening;
                const closing = selectedOption.dataset.closing;

                document.getElementById('courtName').textContent = courtName;
                document.getElementById('courtDetails').innerHTML = `
                            <strong>Horario:</strong> ${opening} - ${closing}<br>
                            <strong>Estado:</strong> <span style="color: #059669;">Disponible</span>
                        `;
                document.getElementById('courtPrice').textContent = price;

                courtInfo.style.display = 'block';
            }

            function hideCourtInfo() {
                courtInfo.style.display = 'none';
                timeSection.style.display = 'none';
                reservationSummary.style.display = 'none';
                submitBtn.disabled = true;
            }

            function loadAvailableTimes() {
                const courtId = courtSelect.value;
                const date = dateInput.value;

                if (!courtId || !date) return;

                timeSection.style.display = 'block';
                document.getElementById('loadingTimes').style.display = 'block';
                document.getElementById('availabilityGrid').innerHTML = '';

                // Simular carga de horarios (reemplaza con tu API real)
                setTimeout(() => {
                    document.getElementById('loadingTimes').style.display = 'none';
                    generateTimeSlots();
                }, 1000);
            }

            function generateTimeSlots() {
                const selectedOption = courtSelect.options[courtSelect.selectedIndex];
                const opening = selectedOption.dataset.opening || '08:00';
                const closing = selectedOption.dataset.closing || '22:00';

                const grid = document.getElementById('availabilityGrid');
                grid.innerHTML = '';

                // Generar slots cada hora
                const startHour = parseInt(opening.split(':')[0]);
                const endHour = parseInt(closing.split(':')[0]);

                for (let hour = startHour; hour < endHour; hour++) {
                    const startTime = `${hour.toString().padStart(2, '0')}:00`;
                    const endTime = `${(hour + 1).toString().padStart(2, '0')}:00`;

                    const slot = document.createElement('div');
                    slot.className = 'time-slot';
                    slot.textContent = `${startTime} - ${endTime}`;
                    slot.dataset.start = startTime;
                    slot.dataset.end = endTime;

                    // Simular disponibilidad (algunas ocupadas)
                    if (Math.random() > 0.7) {
                        slot.classList.add('unavailable');
                        slot.textContent += ' (Ocupado)';
                    } else {
                        slot.addEventListener('click', () => selectTimeSlot(slot));
                    }

                    grid.appendChild(slot);
                }
            }

            function selectTimeSlot(slot) {
                // Remover selección anterior
                if (selectedTimeSlot) {
                    selectedTimeSlot.classList.remove('selected');
                }

                // Seleccionar nuevo slot
                selectedTimeSlot = slot;
                slot.classList.add('selected');

                // Actualizar campos ocultos
                document.getElementById('startTime').value = slot.dataset.start;
                document.getElementById('endTime').value = slot.dataset.end;

                // Mostrar resumen
                updateReservationSummary();

                // Habilitar botón
                submitBtn.disabled = false;
            }

            function updateReservationSummary() {
                if (!selectedTimeSlot || !courtSelect.value) return;

                const courtName = courtSelect.options[courtSelect.selectedIndex].text.split(' - ')[0];
                const price = parseInt(courtSelect.options[courtSelect.selectedIndex].dataset.price);
                const date = new Date(dateInput.value).toLocaleDateString('es-ES');
                const timeText = selectedTimeSlot.textContent;
                const duration = '1 hora';
                const total = price * 1; // 1 hora

                document.getElementById('summaryCourtName').textContent = courtName;
                document.getElementById('summaryDate').textContent = date;
                document.getElementById('summaryTime').textContent = timeText;
                document.getElementById('summaryDuration').textContent = duration;
                document.getElementById('summaryTotal').textContent = total;

                reservationSummary.style.display = 'block';
            }

            // Si hay una cancha preseleccionada
            if (courtSelect.value) {
                showCourtInfo();
            }
        });
    </script>
@endpush