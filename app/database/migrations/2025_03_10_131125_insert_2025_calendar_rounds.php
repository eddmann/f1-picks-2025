<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $races = [
            [
                'year' => 2025,
                'round' => 1,
                'name' => 'Australia',
                'race_qualifying_at' => '2025-03-15 05:00:00',
                'race_at' => '2025-03-16 04:00:00',
            ],
            [
                'year' => 2025,
                'round' => 2,
                'name' => 'China',
                'sprint_qualifying_at' => '2025-03-21 07:30:00',
                'sprint_race_at' => '2025-03-22 03:00:00',
                'race_qualifying_at' => '2025-03-22 07:00:00',
                'race_at' => '2025-03-23 07:00:00',
            ],
            [
                'year' => 2025,
                'round' => 3,
                'name' => 'Japan',
                'race_qualifying_at' => '2025-04-05 06:00:00',
                'race_at' => '2025-04-06 05:00:00',
            ],
            [
                'year' => 2025,
                'round' => 4,
                'name' => 'Bahrain',
                'race_qualifying_at' => '2025-04-12 16:00:00',
                'race_at' => '2025-04-13 15:00:00',
            ],
            [
                'year' => 2025,
                'round' => 5,
                'name' => 'Saudi Arabia',
                'race_qualifying_at' => '2025-04-19 17:00:00',
                'race_at' => '2025-04-20 17:00:00',
            ],
            [
                'year' => 2025,
                'round' => 6,
                'name' => 'Miami',
                'sprint_qualifying_at' => '2025-05-02 20:30:00',
                'sprint_race_at' => '2025-05-03 16:00:00',
                'race_qualifying_at' => '2025-05-03 20:00:00',
                'race_at' => '2025-05-04 20:00:00',
            ],
            [
                'year' => 2025,
                'round' => 7,
                'name' => 'Emilia Romagna',
                'race_qualifying_at' => '2025-05-17 14:00:00',
                'race_at' => '2025-05-18 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 8,
                'name' => 'Monaco',
                'race_qualifying_at' => '2025-05-24 14:00:00',
                'race_at' => '2025-05-25 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 9,
                'name' => 'Spain',
                'race_qualifying_at' => '2025-05-31 14:00:00',
                'race_at' => '2025-06-01 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 10,
                'name' => 'Canada',
                'race_qualifying_at' => '2025-06-14 20:00:00',
                'race_at' => '2025-06-15 18:00:00',
            ],
            [
                'year' => 2025,
                'round' => 11,
                'name' => 'Austria',
                'race_qualifying_at' => '2025-06-28 14:00:00',
                'race_at' => '2025-06-29 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 12,
                'name' => 'United Kingdom',
                'race_qualifying_at' => '2025-07-05 14:00:00',
                'race_at' => '2025-07-06 14:00:00',
            ],
            [
                'year' => 2025,
                'round' => 13,
                'name' => 'Belgium',
                'sprint_qualifying_at' => '2025-07-25 14:30:00',
                'sprint_race_at' => '2025-07-26 10:00:00',
                'race_qualifying_at' => '2025-07-26 14:00:00',
                'race_at' => '2025-07-27 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 14,
                'name' => 'Hungary',
                'race_qualifying_at' => '2025-08-02 14:00:00',
                'race_at' => '2025-08-03 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 15,
                'name' => 'Netherlands',
                'race_qualifying_at' => '2025-08-30 13:00:00',
                'race_at' => '2025-08-31 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 16,
                'name' => 'Italy',
                'race_qualifying_at' => '2025-09-06 14:00:00',
                'race_at' => '2025-09-07 13:00:00',
            ],
            [
                'year' => 2025,
                'round' => 17,
                'name' => 'Azerbaijan',
                'race_qualifying_at' => '2025-09-20 12:00:00',
                'race_at' => '2025-09-21 11:00:00',
            ],
            [
                'year' => 2025,
                'round' => 18,
                'name' => 'Singapore',
                'race_qualifying_at' => '2025-10-04 13:00:00',
                'race_at' => '2025-10-05 12:00:00',
            ],
            [
                'year' => 2025,
                'round' => 19,
                'name' => 'United States',
                'sprint_qualifying_at' => '2025-10-17 21:30:00',
                'sprint_race_at' => '2025-10-18 17:00:00',
                'race_qualifying_at' => '2025-10-18 21:00:00',
                'race_at' => '2025-10-19 19:00:00',
            ],
            [
                'year' => 2025,
                'round' => 20,
                'name' => 'Mexico',
                'race_qualifying_at' => '2025-10-25 21:00:00',
                'race_at' => '2025-10-26 20:00:00',
            ],
            [
                'year' => 2025,
                'round' => 21,
                'name' => 'Brazil',
                'sprint_qualifying_at' => '2025-11-07 18:30:00',
                'sprint_race_at' => '2025-11-08 14:00:00',
                'race_qualifying_at' => '2025-11-08 18:00:00',
                'race_at' => '2025-11-09 17:00:00',
            ],
            [
                'year' => 2025,
                'round' => 22,
                'name' => 'Las Vegas',
                'race_qualifying_at' => '2025-11-22 04:00:00',
                'race_at' => '2025-11-23 04:00:00',
            ],
            [
                'year' => 2025,
                'round' => 23,
                'name' => 'Qatar',
                'sprint_qualifying_at' => '2025-11-28 17:30:00',
                'sprint_race_at' => '2025-11-29 14:00:00',
                'race_qualifying_at' => '2025-11-29 18:00:00',
                'race_at' => '2025-11-30 16:00:00',
            ],
            [
                'year' => 2025,
                'round' => 24,
                'name' => 'Abu Dhabi',
                'race_qualifying_at' => '2025-12-06 14:00:00',
                'race_at' => '2025-12-07 13:00:00',
            ],
        ];

        foreach ($races as $race) {
            DB::table('rounds')->insert($race + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        DB::table('rounds')->where('year', 2025)->delete();
    }
};
