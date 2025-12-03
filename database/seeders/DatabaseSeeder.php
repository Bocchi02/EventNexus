<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = ['admin', 'organizer', 'client', 'guest'];
         foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $adminRole = Role::where('name', 'admin')->first();

        // Create the admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@eventnexus.com'], // unique identifier
            [
                'firstname' => 'System',
                'middlename' => null,
                'lastname' => 'Administrator',
                'password' => Hash::make('password'), // default password
                'status' => 'active',
            ]
        );

        // Assign admin role
        $admin->assignRole($adminRole);
    }
}
