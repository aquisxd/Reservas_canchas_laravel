<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today|before_or_equal:' . Carbon::today()->addMonths(3)->format('Y-m-d'),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'court_id.required' => 'Debes seleccionar una cancha.',
            'court_id.exists' => 'La cancha seleccionada no existe.',
            'reservation_date.required' => 'La fecha de reserva es obligatoria.',
            'reservation_date.date' => 'La fecha debe ser válida.',
            'reservation_date.after_or_equal' => 'No puedes reservar en fechas pasadas.',
            'reservation_date.before_or_equal' => 'No puedes reservar con más de 3 meses de anticipación.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'start_time.date_format' => 'El formato de hora de inicio debe ser HH:MM.',
            'end_time.required' => 'La hora de fin es obligatoria.',
            'end_time.date_format' => 'El formato de hora de fin debe ser HH:MM.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'total_amount.required' => 'El monto total es obligatorio.',
            'total_amount.numeric' => 'El monto total debe ser un número.',
            'total_amount.min' => 'El monto total debe ser mayor a cero.',
            'notes.max' => 'Las notas no pueden exceder los 500 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Si viene como 'date', convertirlo a 'reservation_date'
        if ($this->has('date') && !$this->has('reservation_date')) {
            $this->merge([
                'reservation_date' => $this->input('date'),
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'court_id' => 'cancha',
            'reservation_date' => 'fecha de reserva',
            'start_time' => 'hora de inicio',
            'end_time' => 'hora de fin',
            'total_amount' => 'monto total',
            'notes' => 'notas',
        ];
    }
}
