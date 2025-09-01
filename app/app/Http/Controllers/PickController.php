<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Pick;
use App\Models\Round;
use App\Models\Type;
use Illuminate\Http\Request;

class PickController
{
    public function create(Round $round, Type $type)
    {
        if (! $round->isOpenForPicks($type)) {
            return back()->withErrors(['error' => "Round is not open for {$type->title()} picks"]);
        }

        $drivers = Driver::where('active', true)->orderBy('name', 'asc')->get();
        $pick = Pick::where(['user_id' => auth()->id(), 'round_id' => $round->id, 'type' => $type])->first();

        return view('picks.create', compact('round', 'type', 'drivers', 'pick'));
    }

    public function store(Request $request, Round $round, Type $type)
    {
        if (! $round->isOpenForPicks($type)) {
            return back()->withErrors(['error' => "Round is not open for {$type->title()} picks"]);
        }

        $request->validate([
            'driver1_id' => ['required', 'exists:drivers,id', function ($attribute, $value, $fail) {
                if (! Driver::where('id', $value)->where('active', true)->exists()) {
                    $fail('Selected driver is not active.');
                }
            }],
            'driver2_id' => ['required', 'exists:drivers,id', function ($attribute, $value, $fail) {
                if (! Driver::where('id', $value)->where('active', true)->exists()) {
                    $fail('Selected driver is not active.');
                }
            }],
            'driver3_id' => ['required', 'exists:drivers,id', function ($attribute, $value, $fail) {
                if (! Driver::where('id', $value)->where('active', true)->exists()) {
                    $fail('Selected driver is not active.');
                }
            }],
        ]);

        $driverIds = [$request->input('driver1_id'), $request->input('driver2_id'), $request->input('driver3_id')];
        if (count($driverIds) !== count(array_unique($driverIds))) {
            return back()->withErrors(['error' => 'You must select three different drivers']);
        }

        Pick::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'round_id' => $round->id,
                'type' => $type,
            ],
            [
                'driver1_id' => $request->input('driver1_id'),
                'driver2_id' => $request->input('driver2_id'),
                'driver3_id' => $request->input('driver3_id'),
            ]
        );

        return redirect()
            ->route('rounds.show', $round->id)
            ->with('success', 'Your picks have been saved!');
    }
}
