@extends('layout')

@section('content')
    <form action="{{ route('rounds.index') }}" method="GET">
        <label for="year">Filter by Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    <table>
        <thead>
        <tr>
            <th>Round</th>
            <th>Grand Prix</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($rounds as $round)
            <tr>
                <td>{{ $round->round }}</td>
                <td>{{ $round->name }}</td>
                <td>
                    <a href="{{ route('rounds.show', $round->id) }}">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No rounds</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $rounds->links() }}

    <h2>🏆 Scoreboard</h2>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Score</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($scores as $score)
            <tr>
                <td>{{ $score->name }}</td>
                <td>{{ $score->score }}</td>
                <td>
                    <a href="{{ route('users.show', $score->id) }}">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No users</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
