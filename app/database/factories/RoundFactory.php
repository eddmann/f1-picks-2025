<?php

namespace Database\Factories;

use App\Models\Round;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Round>
 */
class RoundFactory extends Factory
{
    protected $model = Round::class;

    public function definition(): array
    {
        $now = Carbon::now();

        return [
            'year' => (int) $now->year,
            'round' => $this->faker->unique()->numberBetween(1, 24),
            'name' => $this->faker->unique()->city().' Grand Prix',
            'sprint_qualifying_at' => null,
            'sprint_race_at' => null,
            'race_qualifying_at' => $now->copy()->addDay(),
            'race_at' => $now->copy()->addDays(2),
        ];
    }

    public function sprintWeekend(): self
    {
        return $this->state(function (array $attributes) {
            $base = Carbon::now()->addDay();

            return [
                'sprint_qualifying_at' => $base->copy(),
                'sprint_race_at' => $base->copy()->addDay(),
            ];
        });
    }

    public function nonSprintWeekend(): self
    {
        return $this->state(fn () => [
            'sprint_qualifying_at' => null,
            'sprint_race_at' => null,
        ]);
    }
}
