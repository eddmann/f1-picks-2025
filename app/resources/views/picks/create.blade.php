@extends('layout')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="card">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold tracking-tight">Picks for {{ $type->title() }}</h1>
                <span class="badge badge-accent">Round {{ $round->round }}</span>
            </div>

            <form action="{{ route('picks.store', ['round' => $round->id, 'type' => $type]) }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-1">
                        <label for="driver1_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 1</label>
                        <select name="driver1_id" id="driver1_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver1_id', $pick?->driver1_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="driver2_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 2</label>
                        <select name="driver2_id" id="driver2_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver2_id', $pick?->driver2_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="driver3_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 3</label>
                        <select name="driver3_id" id="driver3_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver3_id', $pick?->driver3_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('rounds.show', $round) }}" class="btn btn-muted">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Pick
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const drivers = [...document.getElementsByClassName('driver')];
            const onChange = () => {
                const disable = drivers.map((driver) => driver.value);
                drivers.forEach((driver) => {
                    [...driver.options].forEach((option) => {
                        option.disabled = !option.selected && disable.includes(option.value)
                    });
                });
            };
            drivers.forEach((driver) => driver.addEventListener('change', onChange));
            onChange()
        })();
    </script>
@endsection
