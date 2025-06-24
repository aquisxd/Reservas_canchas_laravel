<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'court_id',
        'start_time',
        'end_time',
        'status',
        'total_price',
        'notes',
        'payment_status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    // Estados de la reserva
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Estados de pago
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Métodos auxiliares
    public function getDurationInHours()
    {
        return $this->start_time->diffInHours($this->end_time);
    }

    public function canBeCancelled()
    {
        // Se puede cancelar hasta 2 horas antes del inicio
        return $this->start_time > now()->addHours(2) &&
               in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function isUpcoming()
    {
        return $this->start_time > now();
    }

    public function isPast()
    {
        return $this->end_time < now();
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_CONFIRMED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            self::STATUS_COMPLETED => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusText()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_CONFIRMED => 'Confirmada',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_COMPLETED => 'Completada',
            default => 'Desconocido'
        };
    }

    // Verificar conflictos de horario
    public static function hasConflict($courtId, $startTime, $endTime, $excludeId = null)
    {
        $query = self::where('court_id', $courtId)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function($subQ) use ($startTime, $endTime) {
                      $subQ->where('start_time', '<=', $startTime)
                           ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Calcular precio total
    public function calculatePrice()
    {
        $hours = $this->getDurationInHours();
        $hourlyRate = $this->court->hourly_rate ?? 0;
        return $hours * $hourlyRate;
    }

    // Actualizar precio automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            if ($reservation->court && (!$reservation->total_price || $reservation->isDirty(['start_time', 'end_time', 'court_id']))) {
                $reservation->total_price = $reservation->calculatePrice();
            }
        });
    }
}
