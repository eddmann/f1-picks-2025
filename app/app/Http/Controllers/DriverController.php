<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController
{
    public function index()
    {
        $drivers = Driver::orderBy('name', 'asc')->get();

        return view('drivers.index', compact('drivers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'drivers' => 'array',
            'drivers.*.id' => 'required|exists:drivers,id',
            'drivers.*.active' => 'sometimes|boolean',
        ]);

        $payload = $request->input('drivers', []);

        foreach ($payload as $driverData) {
            $driver = Driver::find($driverData['id']);
            $driver->active = (bool) ($driverData['active'] ?? false);
            $driver->save();
        }

        return redirect()->route('drivers.index')->with('success', 'Drivers updated');
    }
}
