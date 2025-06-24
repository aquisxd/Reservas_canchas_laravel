<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => 'fiorela123'
        ]);

        // Test client
        User::create([
            'name' => 'Cliente Test',
            'email' => 'cliente@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => 'fiorela123'
        ]);
    }
}
