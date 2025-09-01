<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'round_id', 'type',
        'driver1_id', 'driver2_id', 'driver3_id',
        'score', 'scored_at',
    ];

    public function drivers(): array
    {
        return [
            Driver::find($this->driver1_id),
            Driver::find($this->driver2_id),
            Driver::find($this->driver3_id),
        ];
    }

    public function updateScore(): void
    {
        $result = Result::where(['type' => $this->type, 'round_id' => $this->round_id])->first();

        if ($result === null) {
            return;
        }

        $score = 0;

        $resultDriverIds = array_column($result->drivers(), 'id');
        $pickDriverIds = array_column($this->drivers(), 'id');

        foreach ($resultDriverIds as $idx => $resultDriverId) {
            if ($pickDriverIds[$idx] === $resultDriverId) {
                $score += 2;
            } elseif (in_array($resultDriverId, $pickDriverIds)) {
                $score += 1;
            }
        }

        if ($this->score === $score) {
            return;
        }

        $this->score = $score;
        $this->scored_at = now();
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOpen(): bool
    {
        return $this->round()->first()->isOpenForPicks($this->type);
    }

    protected function casts(): array
    {
        return [
            'type' => Type::class,
        ];
    }
}
