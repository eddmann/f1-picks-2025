@extends('layout')

@section('content')
    <h2>{{ $round->year }} - Round {{ $round->round }}: {{ $round->name }}</h2>

    @if($score !== null)
        <p>🏆 Your Score: {{ $score }}</p>
    @endif

    @foreach($types as $type)
        <h3>{{ $type['id']->title() }}</h3>

        <p>
            <strong>Start:</strong> <time datetime="{{ $type['at']->format('U') }}">{{ $type['at']->format('Y-m-d H:i') }}</time><br />
            <strong>Picks:</strong> <time datetime="{{ $type['pickWindow']->start()->format('U') }}">{{ $type['pickWindow']->start()->format('Y-m-d H:i') }}</time> - <time datetime="{{ $type['pickWindow']->end()->format('U') }}">{{ $type['pickWindow']->end()->format('Y-m-d H:i') }}</time> @if($type['isOpenForPicks'])<a href="{{ route('picks.create', ['round' => $round->id, 'type' => $type['id']]) }}">Pick</a>@endif<br />
            @if($type['result'])<strong>Result:</strong> {{ implode(', ', array_map(fn ($driver) => $driver->name, $type['result']->drivers())) }}@endif
        </p>

        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Picks</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            @forelse($type['picks'] as $pick)
                <tr>
                    <td>{{ $pick->user->name }}</td>
                    <td>{{ implode(', ', array_map(fn ($driver) => $driver->name, $pick->drivers())) }}</td>
                    <td>{{ $pick->score }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No picks</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endforeach

    <script>
        [...document.getElementsByTagName('time')].forEach((time) => {
            time.innerHTML = new Date(time.getAttribute('datetime') * 1e3).toLocaleString();
        });
    </script>
@endsection
