<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:assign-role
                            {email : Email del usuario}
                            {role : Nombre del rol}
                            {--remove : Remover el rol en lugar de asignarlo}
                            {--permission= : Asignar un permiso específico}
                            {--list-roles : Listar todos los roles disponibles}
                            {--list-permissions : Listar todos los permisos disponibles}';

    /**
     * The console command description.
     */
    protected $description = 'Gestionar roles y permisos de usuarios usando Spatie Laravel Permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mostrar roles disponibles
        if ($this->option('list-roles')) {
            $this->showRoles();
            return 0;
        }

        // Mostrar permisos disponibles
        if ($this->option('list-permissions')) {
            $this->showPermissions();
            return 0;
        }

        $email = $this->argument('email');
        $roleName = $this->argument('role');

        // Buscar usuario
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("❌ Usuario con email '{$email}' no encontrado.");
            return 1;
        }

        // Si se especifica un permiso
        if ($permission = $this->option('permission')) {
            return $this->handlePermission($user, $permission);
        }

        // Buscar rol
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("❌ Rol '{$roleName}' no encontrado.");
            $this->showRoles();
            return 1;
        }

        // Remover rol si se especifica la opción
        if ($this->option('remove')) {
            return $this->removeRole($user, $role);
        }

        // Asignar rol
        return $this->assignRole($user, $role);
    }

    /**
     * Asignar rol al usuario
     */
    private function assignRole(User $user, Role $role): int
    {
        if ($user->hasRole($role->name)) {
            $this->warn("⚠️  El usuario {$user->name} ya tiene el rol '{$role->name}'.");
            return 0;
        }

        $user->assignRole($role);

        $this->info("✅ Rol '{$role->name}' asignado exitosamente a {$user->name} ({$user->email}).");
        $this->showUserRoles($user);

        return 0;
    }

    /**
     * Remover rol del usuario
     */
    private function removeRole(User $user, Role $role): int
    {
        if (!$user->hasRole($role->name)) {
            $this->warn("⚠️  El usuario {$user->name} no tiene el rol '{$role->name}'.");
            return 0;
        }

        $user->removeRole($role);

        $this->info("✅ Rol '{$role->name}' removido exitosamente de {$user->name} ({$user->email}).");
        $this->showUserRoles($user);

        return 0;
    }

    /**
     * Manejar permisos
     */
    private function handlePermission(User $user, string $permissionName): int
    {
        $permission = Permission::where('name', $permissionName)->first();
        if (!$permission) {
            $this->error("❌ Permiso '{$permissionName}' no encontrado.");
            $this->showPermissions();
            return 1;
        }

        if ($this->option('remove')) {
            $user->revokePermissionTo($permission);
            $this->info("✅ Permiso '{$permission->name}' removido de {$user->name}.");
        } else {
            $user->givePermissionTo($permission);
            $this->info("✅ Permiso '{$permission->name}' asignado a {$user->name}.");
        }

        $this->showUserPermissions($user);
        return 0;
    }

    /**
     * Mostrar roles disponibles
     */
    private function showRoles(): void
    {
        $this->info("📋 Roles disponibles:");
        $roles = Role::all();

        foreach ($roles as $role) {
            $permissionCount = $role->permissions->count();
            $this->line("  • {$role->name} ({$permissionCount} permisos)");
        }
    }

    /**
     * Mostrar permisos disponibles
     */
    private function showPermissions(): void
    {
        $this->info("🔑 Permisos disponibles:");
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[0];
        });

        foreach ($permissions as $group => $groupPermissions) {
            $this->line("  📂 Grupo: {$group}");
            foreach ($groupPermissions as $permission) {
                $this->line("    • {$permission->name}");
            }
        }
    }

    /**
     * Mostrar roles actuales del usuario
     */
    private function showUserRoles(User $user): void
    {
        $user->refresh();
        $this->info("👤 Roles actuales de {$user->name}:");

        if ($user->roles->isEmpty()) {
            $this->line("  • Sin roles asignados");
        } else {
            foreach ($user->roles as $role) {
                $this->line("  • {$role->name}");
            }
        }
    }

    /**
     * Mostrar permisos actuales del usuario
     */
    private function showUserPermissions(User $user): void
    {
        $user->refresh();
        $this->info("🔑 Permisos directos de {$user->name}:");

        $directPermissions = $user->getDirectPermissions();
        if ($directPermissions->isEmpty()) {
            $this->line("  • Sin permisos directos");
        } else {
            foreach ($directPermissions as $permission) {
                $this->line("  • {$permission->name}");
            }
        }

        $this->info("🔑 Todos los permisos de {$user->name} (incluidos por roles):");
        $allPermissions = $user->getAllPermissions();
        if ($allPermissions->isEmpty()) {
            $this->line("  • Sin permisos");
        } else {
            foreach ($allPermissions as $permission) {
                $this->line("  • {$permission->name}");
            }
        }
    }
}
