@extends('layout')

@section('content')
    <h1>Round Results</h1>

    <form action="{{ route('results.store') }}" method="POST">
        @csrf

        <div>
            <label for="round_id">Round:</label>
            <select name="round_id" id="round_id">
                <option value="0">Pick a round</option>
                @foreach($rounds as $round)
                    <option value="{{ $round->id }}" {{ old('round_id') == $round->id ? 'selected' : '' }}>{{ $round->year }} - Round {{ $round->round }} ({{ $round->name }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="">Pick a type</option>
                @foreach(\App\Models\Type::cases() as $type)
                    <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ $type->title() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="driver1_id">Driver 1:</label>
            <select name="driver1_id" id="driver1_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver1_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="driver2_id">Driver 2:</label>
            <select name="driver2_id" id="driver2_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver2_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="driver3_id">Driver 3:</label>
            <select name="driver3_id" id="driver3_id" class="driver">
                <option value="0">Pick a driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver3_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Save</button>
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
