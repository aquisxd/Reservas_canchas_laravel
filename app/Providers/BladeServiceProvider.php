<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Directivas para roles específicos
        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->hasRole('admin')): ?>";
        });

        Blade::directive('endadmin', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('superadmin', function () {
            return "<?php if(auth()->check() && auth()->user()->hasRole('super_admin')): ?>";
        });

        Blade::directive('endsuperadmin', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('courtowner', function () {
            return "<?php if(auth()->check() && auth()->user()->hasRole('court_owner')): ?>";
        });

        Blade::directive('endcourtowner', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('client', function () {
            return "<?php if(auth()->check() && auth()->user()->hasRole('client')): ?>";
        });

        Blade::directive('endclient', function () {
            return '<?php endif; ?>';
        });

        // Directiva para verificar cualquier rol administrativo
        Blade::directive('adminrole', function () {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin'])): ?>";
        });

        Blade::directive('endadminrole', function () {
            return '<?php endif; ?>';
        });

        // Directiva para verificar si puede gestionar canchas
        Blade::directive('canmanagecourts', function () {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super_admin', 'court_owner'])): ?>";
        });

        Blade::directive('endcanmanagecourts', function () {
            return '<?php endif; ?>';
        });
    }
}
