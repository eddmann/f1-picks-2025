<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $drivers = [
            ['name' => 'Max Verstappen'],
            ['name' => 'Liam Lawson'],
            ['name' => 'Lando Norris'],
            ['name' => 'Oscar Piastri'],
            ['name' => 'Charles Leclerc'],
            ['name' => 'Lewis Hamilton'],
            ['name' => 'George Russell'],
            ['name' => 'Andrea Kimi Antonelli'],
            ['name' => 'Fernando Alonso'],
            ['name' => 'Lance Stroll'],
            ['name' => 'Pierre Gasly'],
            ['name' => 'Jack Doohan'],
            ['name' => 'Esteban Ocon'],
            ['name' => 'Oliver Bearman'],
            ['name' => 'Isack Hadjar'],
            ['name' => 'Yuki Tsunoda'],
            ['name' => 'Nico Hulkenberg'],
            ['name' => 'Gabriel Bortoleto'],
            ['name' => 'Alexander Albon'],
            ['name' => 'Carlos Sainz'],
        ];

        foreach ($drivers as $driver) {
            DB::table('drivers')->updateOrInsert(
                $driver,
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
};
