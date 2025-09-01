<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Result;
use App\Models\Round;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Result>
 */
class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function definition(): array
    {
        $drivers = Driver::factory()->count(3)->create();

        return [
            'round_id' => Round::factory(),
            'type' => $this->faker->randomElement(array_column(Type::cases(), 'value')),
            'driver1_id' => $drivers[0]->id,
            'driver2_id' => $drivers[1]->id,
            'driver3_id' => $drivers[2]->id,
        ];
    }
}
