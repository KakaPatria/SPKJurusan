<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder inti untuk kebutuhan aplikasi
        // Jalankan otomatis saat migrate:fresh --seed
        $this->call([
            AdminSeeder::class,
            PolijeMajorSeeder::class,
            // AlumniSeeder::class, // Aktifkan jika butuh data evaluasi alumni
        ]);
    }
}
