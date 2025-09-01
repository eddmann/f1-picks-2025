<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Pick;
use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pick>
 */
class PickFactory extends Factory
{
    protected $model = Pick::class;

    public function definition(): array
    {
        $drivers = Driver::factory()->count(3)->create();

        return [
            'user_id' => User::factory(),
            'round_id' => Round::factory(),
            'type' => $this->faker->randomElement(array_column(Type::cases(), 'value')),
            'driver1_id' => $drivers[0]->id,
            'driver2_id' => $drivers[1]->id,
            'driver3_id' => $drivers[2]->id,
            'score' => null,
            'scored_at' => null,
        ];
    }
}
