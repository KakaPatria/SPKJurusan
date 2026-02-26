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
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Polije',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create BK (Konselor) User
        User::firstOrCreate(
            ['email' => 'bk@gmail.com'],
            [
                'name' => 'Konselor BK',
                'password' => Hash::make('bk123'),
                'role' => 'bk',
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Admin & BK users created successfully!\n";
        echo "Admin: admin@gmail.com / admin123\n";
        echo "BK: bk@gmail.com / bk123\n";
    }
}
