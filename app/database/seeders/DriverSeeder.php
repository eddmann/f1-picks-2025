<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = [
            ['name' => 'Max Verstappen'],
            ['name' => 'Sergio Perez'],
            ['name' => 'Lewis Hamilton'],
            ['name' => 'George Russell'],
            ['name' => 'Charles Leclerc'],
            ['name' => 'Carlos Sainz'],
            ['name' => 'Fernando Alonso'],
            ['name' => 'Lance Stroll'],
            ['name' => 'Lando Norris'],
            ['name' => 'Oscar Piastri'],
            ['name' => 'Esteban Ocon'],
            ['name' => 'Pierre Gasly'],
            ['name' => 'Valtteri Bottas'],
            ['name' => 'Zhou Guanyu'],
            ['name' => 'Kevin Magnussen'],
            ['name' => 'Nico Hulkenberg'],
            ['name' => 'Yuki Tsunoda'],
            ['name' => 'Daniel Ricciardo'],
            ['name' => 'Alexander Albon'],
            ['name' => 'Logan Sargeant'],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
