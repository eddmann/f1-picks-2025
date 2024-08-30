<?php

use App\Models\Round;
use Illuminate\Support\Facades\Artisan;

// php artisan round:create --year=2024 --round=1 --name="Sample Race" --sprint-qualifying-at="2024-01-01 11:00:00" --sprint-race-at="2024-01-01 12:00:00" --race-qualifying-at="2024-01-01 13:00:00" --race-at="2024-01-01 14:00:00"

Artisan::command('round:create {--year=} {--round=} {--name=} {--race-qualifying-at=} {--race-at=} {--sprint-qualifying-at=} {--sprint-race-at=}', function () {
    $round = Round::create([
        'year' => $this->options()['year'],
        'round' => $this->options()['round'],
        'name' => $this->options()['name'],
        'sprint_qualifying_at' => $this->options()['sprint-qualifying-at'],
        'sprint_race_at' => $this->options()['sprint-race-at'],
        'race_qualifying_at' => $this->options()['race-qualifying-at'],
        'race_at' => $this->options()['race-at'],
    ]);

    $this->info("Successfully created new round #{$round->id}");
})->purpose('Create a new F1 Grand Prix round');
