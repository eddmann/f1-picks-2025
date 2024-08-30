@extends('layout')

@section('content')
    <h1>Picks for {{ $type->title() }}</h1>

    <form action="{{ route('picks.store', ['round' => $round->id, 'type' => $type]) }}" method="POST">
        @csrf

        <div>
            <label for="driver1_id">Driver 1</label>
            <select name="driver1_id" id="driver1_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver1_id', $pick?->driver1_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="driver2_id">Driver 2</label>
            <select name="driver2_id" id="driver2_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver2_id', $pick?->driver2_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="driver3_id">Driver 3</label>
            <select name="driver3_id" id="driver3_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver3_id', $pick?->driver3_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Pick</button>
    </form>

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
