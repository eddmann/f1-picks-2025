<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 2025 calendar added using migration
            // DriverSeeder::class,
            // RoundSeeder::class,
            UserSeeder::class,
        ]);
    }
}
