<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'location',
        'phone',
        'price_per_hour',
        'surface_type',
        'amenities',
        'opening_time',
        'closing_time',
        'status',
        'rules',
        'capacity',
        'image',
    ];

    protected $casts = [
        'amenities' => 'array',
        'price_per_hour' => 'decimal:2',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByOwner($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAvailable($query, $date = null, $startTime = null, $endTime = null)
    {
        $query->where('status', 'active');

        if ($date && $startTime && $endTime) {
            $query->whereDoesntHave('reservations', function ($q) use ($date, $startTime, $endTime) {
                $q->where('reservation_date', $date)
                  ->where('status', '!=', 'cancelled')
                  ->where(function ($timeQuery) use ($startTime, $endTime) {
                      $timeQuery->whereBetween('start_time', [$startTime, $endTime])
                               ->orWhereBetween('end_time', [$startTime, $endTime])
                               ->orWhere(function ($overlapQuery) use ($startTime, $endTime) {
                                   $overlapQuery->where('start_time', '<=', $startTime)
                                              ->where('end_time', '>=', $endTime);
                               });
                  });
            });
        }

        return $query;
    }

    // Mutators y Accessors
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price_per_hour, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Activa</span>',
            'inactive' => '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Inactiva</span>',
            'maintenance' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Mantenimiento</span>',
        ];

        return $badges[$this->status] ?? $badges['inactive'];
    }

    public function getSurfaceTypeNameAttribute()
    {
        $types = [
            'clay' => 'Arcilla',
            'hard' => 'Dura',
            'grass' => 'Césped',
            'synthetic' => 'Sintética',
        ];

        return $types[$this->surface_type] ?? $this->surface_type;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default-court.jpg'); // Imagen por defecto
    }

    // Métodos auxiliares
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isAvailableAt($date, $startTime, $endTime): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Verificar horarios de operación
        $openingTime = $this->opening_time->format('H:i');
        $closingTime = $this->closing_time->format('H:i');

        if ($startTime < $openingTime || $endTime > $closingTime) {
            return false;
        }

        // Verificar conflictos con reservas existentes
        return !$this->reservations()
            ->where('reservation_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($overlapQuery) use ($startTime, $endTime) {
                          $overlapQuery->where('start_time', '<=', $startTime)
                                     ->where('end_time', '>=', $endTime);
                      });
            })
            ->exists();
    }

    public function getAvailableHours($date)
    {
        $availableHours = [];
        $opening = $this->opening_time->format('H:i');
        $closing = $this->closing_time->format('H:i');

        // Generar intervalos de 1 hora
        $current = $opening;
        while ($current < $closing) {
            $nextHour = date('H:i', strtotime($current . ' +1 hour'));

            if ($this->isAvailableAt($date, $current, $nextHour)) {
                $availableHours[] = [
                    'start' => $current,
                    'end' => $nextHour,
                    'display' => $current . ' - ' . $nextHour
                ];
            }

            $current = $nextHour;
        }

        return $availableHours;
    }

    public function getTotalReservationsAttribute()
    {
        return $this->reservations()->count();
    }

    public function getMonthlyRevenueAttribute()
    {
        return $this->reservations()
            ->where('status', 'confirmed')
            ->whereMonth('reservation_date', now()->month)
            ->whereYear('reservation_date', now()->year)
            ->sum('total_price');
    }
}
