<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use App\Models\Court;
use App\Models\Reservation;
use App\Policies\CourtPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Court::class => CourtPolicy::class,
        Reservation::class => ReservationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
