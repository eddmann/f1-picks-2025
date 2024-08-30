<?php

namespace App\Models;

// @TODO think of a better name than type?
enum Type: string
{
    case SPRINT_QUALIFYING = 'sprint_qualifying';
    case SPRINT_RACE = 'sprint_race';
    case RACE_QUALIFYING = 'race_qualifying';
    case RACE = 'race';

    public function title(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }
}
