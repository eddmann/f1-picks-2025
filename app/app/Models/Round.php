<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'year', 'round', 'name',
        'sprint_qualifying_at', 'sprint_race_at',
        'race_qualifying_at', 'race_at'
    ];

    public function isOpenForPicks(Type $type): bool
    {
        return match ($type) {
            Type::SPRINT_QUALIFYING => (bool) $this->getSprintQualifyingPickWindow()?->contains(now()),
            Type::SPRINT_RACE => (bool) $this->getSprintRacePickWindow()?->contains(now()),
            Type::RACE_QUALIFYING => $this->getRaceQualifyingPickWindow()->contains(now()),
            Type::RACE => $this->getRacePickWindow()->contains(now()),
        };
    }

    public function getTypeAt(Type $type): ?CarbonImmutable
    {
        return match ($type) {
            Type::SPRINT_QUALIFYING => $this->sprint_qualifying_at,
            Type::SPRINT_RACE => $this->sprint_race_at,
            Type::RACE_QUALIFYING => $this->race_qualifying_at,
            Type::RACE => $this->race_at,
        };
    }

    public function getPickWindow(Type $type): ?CarbonPeriod
    {
        return match ($type) {
            Type::SPRINT_QUALIFYING => $this->getSprintQualifyingPickWindow(),
            Type::SPRINT_RACE => $this->getSprintRacePickWindow(),
            Type::RACE_QUALIFYING => $this->getRaceQualifyingPickWindow(),
            Type::RACE => $this->getRacePickWindow(),
        };
    }

    private function getSprintQualifyingPickWindow(): ?CarbonPeriod
    {
        if (! $this->isSprintWeekend()) {
            return null;
        }

        return CarbonPeriod::between($this->sprint_qualifying_at->sub('1 day'), $this->sprint_qualifying_at->sub('5 minutes'));
    }

    private function getSprintRacePickWindow(): ?CarbonPeriod
    {
        if (! $this->isSprintWeekend()) {
            return null;
        }

        return CarbonPeriod::between($this->sprint_qualifying_at->add('1 hour'), $this->sprint_race_at->sub('5 minutes'));
    }

    private function getRaceQualifyingPickWindow(): CarbonPeriod
    {
        if ($this->isSprintWeekend()) {
            return CarbonPeriod::between($this->sprint_race_at->add('1 hour'), $this->race_qualifying_at->sub('5 minutes'));
        }

        return CarbonPeriod::between($this->race_qualifying_at->sub('1 day'), $this->race_qualifying_at->sub('5 minutes'));
    }

    private function getRacePickWindow(): CarbonPeriod
    {
        return CarbonPeriod::between($this->race_qualifying_at->add('1 hour'), $this->race_at->sub('5 minutes'));
    }

    private function isSprintWeekend(): bool
    {
        return $this->sprint_qualifying_at !== null && $this->sprint_race_at;
    }

    public function picks()
    {
        return $this->hasMany(Pick::class);
    }

    protected function casts(): array
    {
        return [
            'sprint_qualifying_at' => 'immutable_datetime',
            'sprint_race_at' => 'immutable_datetime',
            'race_qualifying_at' => 'immutable_datetime',
            'race_at' => 'immutable_datetime',
        ];
    }
}
