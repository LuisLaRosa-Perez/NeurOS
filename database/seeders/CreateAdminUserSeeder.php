<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing user if any
        $user = User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->delete();
        }

        // Create the admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Use Hash::make for Bcrypt
        ]);

        // Ensure the 'administrador' role exists
        $adminRole = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);

        // Assign the 'administrador' role to the admin user
        $adminUser->assignRole($adminRole);
    }
}
