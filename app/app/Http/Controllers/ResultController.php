<?php

namespace App\Http\Controllers;

use App\Jobs\CalculatePickScoresForResult;
use App\Models\Driver;
use App\Models\Result;
use App\Models\Round;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class ResultController extends Controller
{
    public function create()
    {
        $rounds = Round::all();
        $drivers = Driver::where('active', true)->orderBy('name', 'asc')->get();

        $now = now();
        $year = (int) date('Y');
        $activeRound = Round::where('year', $year)
            ->where('race_at', '>=', $now)
            ->orderBy('race_at', 'asc')
            ->first();

        if (! $activeRound) {
            $activeRound = Round::where('year', $year)
                ->orderBy('round', 'desc')
                ->first();
        }

        $activeRoundId = $activeRound?->getKey();

        return view('results.create', compact('rounds', 'drivers', 'activeRoundId'));
    }

    public function store(Request $request)
    {
        // @TODO review possible use of form request
        $request->validate([
            'round_id' => 'required|exists:rounds,id',
            'type' => ['required', Rule::enum(Type::class)],
            'driver1_id' => 'required|exists:drivers,id',
            'driver2_id' => 'required|exists:drivers,id',
            'driver3_id' => 'required|exists:drivers,id',
        ]);

        $round = Round::find($request->input('round_id'));

        if (in_array($request->input('type'), ['sprint_qualifying', 'sprint_race']) && ! $round->isSprintWeekend()) {
            return back()->withErrors(['error' => 'This round is not a sprint weekend']);
        }

        $driverIds = [$request->input('driver1_id'), $request->input('driver2_id'), $request->input('driver3_id')];
        if (count($driverIds) !== count(array_unique($driverIds))) {
            return back()->withErrors(['error' => 'You must select three different drivers']);
        }

        $result = Result::updateOrCreate(
            [
                'round_id' => $request->round_id,
                'type' => $request->type,
            ],
            [
                'driver1_id' => $request->driver1_id,
                'driver2_id' => $request->driver2_id,
                'driver3_id' => $request->driver3_id,
            ]
        );

        CalculatePickScoresForResult::dispatch($result->round_id, $result->type);

        return redirect()
            ->route('results.create')
            ->with('success', 'Results have been saved!');
    }
}
