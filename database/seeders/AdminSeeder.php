<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@polije.ac.id'],
            [
                'name' => 'Admin Polije',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create BK (Konselor) User
        User::firstOrCreate(
            ['email' => 'gurubk@polije.ac.id'],
            [
                'name' => 'Konselor BK',
                'password' => Hash::make('gurubk1234'),
                'role' => 'bk',
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Admin & BK users created successfully!\n";
        echo "Admin: admin@polije.ac.id / admin1234\n";
        echo "BK: gurubk@polije.ac.id / gurubk1234\n";
    }
}
