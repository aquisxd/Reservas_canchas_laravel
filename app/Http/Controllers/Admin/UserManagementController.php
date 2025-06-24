<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:view users']);
    }

    /**
     * Mostrar lista de usuarios
     */
    public function index(Request $request)
    {
        $this->authorize('view users');

        $query = User::with('roles');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->authorize('create users');

        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Crear nuevo usuario
     */
    public function store(Request $request)
    {
        $this->authorize('create users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Asignar roles
        $user->assignRole($request->roles);

        // Asignar permisos directos (opcional)
        if ($request->filled('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar usuario específico
     */
    public function show(User $user)
    {
        $this->authorize('view users');

        $user->load(['roles', 'permissions', 'reservations.court', 'courts']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $user)
    {
        $this->authorize('edit users');

        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('edit users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'is_active' => ['boolean'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Sincronizar roles
        $user->syncRoles($request->roles);

        // Sincronizar permisos directos
        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
        $this->authorize('delete users');

        // Prevenir eliminar al último super admin
        if ($user->hasRole('super_admin')) {
            $superAdminCount = User::role('super_admin')->where('is_active', true)->count();
            if ($superAdminCount <= 1) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el último super administrador activo.');
            }
        }

        // Prevenir auto-eliminación
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'No puede eliminar su propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('edit users');

        // Prevenir desactivar al último super admin
        if ($user->hasRole('super_admin') && $user->is_active) {
            $superAdminCount = User::role('super_admin')->where('is_active', true)->count();
            if ($superAdminCount <= 1) {
                return redirect()->back()
                    ->with('error', 'No se puede desactivar el último super administrador activo.');
            }
        }

        // Prevenir auto-desactivación
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'No puede desactivar su propia cuenta.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->back()
            ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Cambiar roles de usuario
     */
    public function changeRoles(Request $request, User $user)
    {
        $this->authorize('manage user roles');

        $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->syncRoles($request->roles);

        return redirect()->back()
            ->with('success', 'Roles actualizados exitosamente.');
    }

    /**
     * Asignar permisos directos
     */
    public function assignPermissions(Request $request, User $user)
    {
        $this->authorize('manage user roles');

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->back()
            ->with('success', 'Permisos actualizados exitosamente.');
    }
}
