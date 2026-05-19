<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bkUsers = [
            ['email' => 'gurubk1@polije.ac.id', 'name' => 'Konselor BK 1', 'password' => 'bk123456'],
            ['email' => 'gurubk2@polije.ac.id', 'name' => 'Konselor BK 2', 'password' => 'bk234567'],
            ['email' => 'gurubk3@polije.ac.id', 'name' => 'Konselor BK 3', 'password' => 'bk345678'],
        ];

        foreach ($bkUsers as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'role' => 'bk',
                    'email_verified_at' => now(),
                ]
            );

            echo "Created or exists: {$u['email']} / {$u['password']}\n";
        }

        echo "✅ 3 BK accounts seeded.\n";
    }
}
