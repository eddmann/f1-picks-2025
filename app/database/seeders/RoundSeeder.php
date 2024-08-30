<?php

namespace Database\Seeders;

use App\Models\Round;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class RoundSeeder extends Seeder
{
    private const FORMAT = 'Y-m-d H:i:s';

    public function run(): void
    {
        $rounds = [
            [
                'number' => 15,
                'name' => 'Netherlands',
                'race_qualifying_at' => $this->toDateTime('2024-08-24 13:00:00'),
                'race_at' => $this->toDateTime('2024-08-25 13:00:00'),
            ],
        ];

        foreach ($rounds as $round) {
            Round::create([
                'year' => 2024,
                'round' => $round['number'],
                'name' => $round['name'],
                'sprint_qualifying_at' => $round['sprint_qualifying_at'] ?? null,
                'sprint_race_at' => $round['sprint_race_at'] ?? null,
                'race_qualifying_at' => $round['race_qualifying_at'],
                'race_at' => $round['race_at'],
            ]);
        }
    }

    private function toDateTime(string $dateTime): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat(self::FORMAT, $dateTime, new \DateTimeZone('UTC'));
    }
}
