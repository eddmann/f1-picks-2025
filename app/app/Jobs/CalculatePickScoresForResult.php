<?php

namespace App\Jobs;

use App\Models\Pick;
use App\Models\Type;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CalculatePickScoresForResult implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $roundId, private readonly Type $type) {}

    public function handle(): void
    {
        $picks = Pick::where(['round_id' => $this->roundId, 'type' => $this->type])->get();

        foreach ($picks as $pick) {
            $pick->updateScore();
            $pick->save();
        }
    }
}
