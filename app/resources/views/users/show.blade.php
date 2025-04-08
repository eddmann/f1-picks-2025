@extends('layout')

@section('content')
    <h2>{{ $user->name }}</h2>

    <form action="{{ route('users.show', $user->id) }}" method="GET">
        <label for="year">Filter by Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    @foreach($picks as $round => $picksForRound)
        <h3>{{ $round }}</h3>

        <table>
            <thead>
            <tr>
                <th>Type</th>
                <th>Picks</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            @foreach($picksForRound as $pick)
                <tr>
                    <td>{{ $pick->type->title() }}</td>
                    <td>
                        @if ($pick->isOpen())
                            ...
                        @else
                            {{ implode(', ', array_map(fn ($driver) => $driver->name, $pick->drivers())) }}
                        @endif
                    </td>
                    <td>{{ $pick->score }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
@endsection
