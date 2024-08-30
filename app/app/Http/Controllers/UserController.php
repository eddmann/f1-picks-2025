<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    public function show(User $user, Request $request)
    {
        $year = $request->input('year') ?: date('Y');
        $years = Round::groupBy('year')->pluck('year');

        $picks = $user->picks()
            ->with('round')
            ->join('rounds', 'rounds.id', '=', 'picks.round_id')
            ->where('year', $year)
            ->get()
            ->groupBy(fn ($pick) => $pick->name);

        return view('users.show', compact('user', 'year', 'years', 'picks'));
    }
}
