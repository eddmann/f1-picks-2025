<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id', 'type',
        'driver1_id', 'driver2_id', 'driver3_id',
    ];

    public function drivers(): array
    {
        return [
            Driver::find($this->driver1_id),
            Driver::find($this->driver2_id),
            Driver::find($this->driver3_id),
        ];
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    protected function casts(): array
    {
        return [
            'type' => Type::class,
        ];
    }
}
