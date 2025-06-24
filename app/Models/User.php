<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relación con reservas
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Relación con canchas (si es propietario)
     * Actualizada para ser compatible con el controller
     */
    public function courts(): HasMany
    {
        return $this->hasMany(Court::class, 'owner_id');
    }

    /**
     * Alias para mantener compatibilidad
     */
    public function ownedCourts(): HasMany
    {
        return $this->courts();
    }

    /**
     * Verificar si es administrador (cualquier tipo)
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Verificar si es cliente
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Verificar si es propietario de cancha
     */
    public function isCourtOwner(): bool
    {
        return $this->hasRole('court_owner');
    }

    /**
     * Verificar si es super administrador
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Actualizar último login
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios por rol (compatible con Spatie)
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->role($role);
    }

    /**
     * Obtener el rol principal del usuario
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roles->first()?->name;
    }

    /**
     * Obtener roles como string para compatibilidad
     */
    public function getRoleAttribute(): ?string
    {
        // Mantener compatibilidad con el código anterior
        if ($this->hasRole('super_admin')) {
            return 'admin'; // Para compatibilidad
        }
        if ($this->hasRole('admin')) {
            return 'admin';
        }
        if ($this->hasRole('court_owner')) {
            return 'court_owner';
        }
        if ($this->hasRole('client')) {
            return 'client';
        }

        return $this->getPrimaryRoleAttribute();
    }

    /**
     * Verificar si puede gestionar un recurso específico
     */
    public function canManage($resource): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($resource instanceof Court) {
            return $this->id === $resource->owner_id;
        }

        if ($resource instanceof Reservation) {
            return $this->id === $resource->user_id;
        }

        return false;
    }

    /**
     * Obtener estadísticas del usuario
     */
    public function getStatsAttribute(): array
    {
        return [
            'total_reservations' => $this->reservations()->count(),
            'total_courts' => $this->courts()->count(),
            'total_spent' => $this->reservations()
                ->where('payment_status', 'completed')
                ->sum('total_price') ?? 0,
            'active_reservations' => $this->reservations()
                ->where('status', 'confirmed')
                ->where('date', '>=', today())
                ->count(),
        ];
    }

    /**
     * Scope para buscar usuarios
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Obtener avatar o iniciales
     */
    public function getAvatarAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Verificar si el usuario está verificado
     */
    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Obtener color del rol para la UI
     */
    public function getRoleColorAttribute(): string
    {
        if ($this->hasRole(['super_admin', 'admin'])) {
            return 'red';
        }
        if ($this->hasRole('court_owner')) {
            return 'blue';
        }
        return 'green';
    }

    /**
     * Obtener nombre del rol para mostrar
     */
    public function getRoleDisplayNameAttribute(): string
    {
        if ($this->hasRole('super_admin')) {
            return 'Super Administrador';
        }
        if ($this->hasRole('admin')) {
            return 'Administrador';
        }
        if ($this->hasRole('court_owner')) {
            return 'Propietario de Cancha';
        }
        if ($this->hasRole('client')) {
            return 'Cliente';
        }

        return 'Sin Rol';
    }

    /**
     * Boot del modelo para eventos
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un usuario, asignar rol por defecto si no tiene ninguno
        static::created(function ($user) {
            if ($user->roles->isEmpty()) {
                $user->assignRole('client');
            }
        });
    }
}
