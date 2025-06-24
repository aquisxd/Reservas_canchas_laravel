<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar cache de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Gestión de usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',

            // Gestión de canchas
            'view all courts',
            'create courts',
            'edit own courts',
            'edit all courts',
            'delete own courts',
            'delete all courts',
            'view own courts',
            'manage court status',

            // Gestión de reservas
            'view all reservations',
            'create reservations',
            'edit own reservations',
            'edit all reservations',
            'cancel own reservations',
            'cancel all reservations',
            'view own reservations',
            'approve reservations',
            'reject reservations',

            // Dashboard y reportes
            'view admin dashboard',
            'view owner dashboard',
            'view client dashboard',
            'view reports',
            'export reports',

            // Gestión de reservas de canchas propias
            'manage court reservations',
            'view court reservations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // ROL: Super Admin
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // ROL: Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',
            'view all courts',
            'edit all courts',
            'delete all courts',
            'manage court status',
            'view all reservations',
            'edit all reservations',
            'cancel all reservations',
            'approve reservations',
            'reject reservations',
            'view admin dashboard',
            'view reports',
            'export reports',
        ]);

        // ROL: Propietario de Cancha
        $courtOwnerRole = Role::create(['name' => 'court_owner']);
        $courtOwnerRole->givePermissionTo([
            'view own courts',
            'create courts',
            'edit own courts',
            'delete own courts',
            'create reservations',
            'edit own reservations',
            'cancel own reservations',
            'view own reservations',
            'manage court reservations',
            'view court reservations',
            'approve reservations',
            'reject reservations',
            'view owner dashboard',
        ]);

        // ROL: Cliente
        $clientRole = Role::create(['name' => 'client']);
        $clientRole->givePermissionTo([
            'create reservations',
            'edit own reservations',
            'cancel own reservations',
            'view own reservations',
            'view client dashboard',
        ]);

        // Crear usuarios de ejemplo

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('superadmin123'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole($adminRole);

        // Propietario de Cancha
        $courtOwner = User::create([
            'name' => 'Propietario de Cancha',
            'email' => 'propietario@ejemplo.com',
            'password' => Hash::make('propietario123'),
            'phone' => '+51987654321',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $courtOwner->assignRole($courtOwnerRole);

        // Cliente
        $client = User::create([
            'name' => 'Cliente Ejemplo',
            'email' => 'cliente@ejemplo.com',
            'password' => Hash::make('cliente123'),
            'phone' => '+51123456789',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $client->assignRole($clientRole);

        // Usuario con múltiples roles (Cliente y Propietario)
        $multiRoleUser = User::create([
            'name' => 'Usuario Múltiple',
            'email' => 'multiple@ejemplo.com',
            'password' => Hash::make('multiple123'),
            'phone' => '+51555666777',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $multiRoleUser->assignRole([$clientRole, $courtOwnerRole]);

        $this->command->info('Roles y permisos creados exitosamente con Spatie Laravel Permission!');
        $this->command->info('Usuarios de prueba creados:');
        $this->command->info('- Super Admin: superadmin@admin.com / superadmin123');
        $this->command->info('- Admin: admin@admin.com / admin123');
        $this->command->info('- Propietario: propietario@ejemplo.com / propietario123');
        $this->command->info('- Cliente: cliente@ejemplo.com / cliente123');
        $this->command->info('- Múltiple: multiple@ejemplo.com / multiple123');
    }
}
