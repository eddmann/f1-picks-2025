@extends('layout')

@section('content')
    <div class="mb-6 flex flex-col items-start justify-between gap-3 md:flex-row md:items-center">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">{{ $round->year }} • Round {{ $round->round }}</h2>
            <p class="text-sm text-neutral-400">{{ $round->name }}</p>
        </div>
        @if($score !== null)
            <div class="rounded-md bg-neutral-900/60 px-3 py-1.5 text-sm">
                <span class="text-neutral-400">Your Score:</span>
                <span class="ml-2 font-semibold text-red-400">{{ $score }}</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($types as $type)
            <section class="card">
                <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <h3 class="text-lg font-semibold">{{ $type['id']->title() }}</h3>
                    <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs text-neutral-300">
                        <span class="badge flex max-w-full flex-wrap whitespace-normal break-words">Start: <time class="ml-1" datetime="{{ $type['at']->format('U') }}">{{ $type['at']->format('Y-m-d H:i') }}</time></span>
                        <span class="badge flex max-w-full flex-wrap whitespace-normal break-words">Picks: <time class="ml-1" datetime="{{ $type['pickWindow']->start()->format('U') }}">{{ $type['pickWindow']->start()->format('Y-m-d H:i') }}</time> - <time datetime="{{ $type['pickWindow']->end()->format('U') }}">{{ $type['pickWindow']->end()->format('Y-m-d H:i') }}</time></span>
                    </div>
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <div class="text-sm text-neutral-300">
                        @if($type['result'])
                            <span class="text-neutral-400">Result:</span>
                            <span class="ml-1">{{ implode(', ', array_map(fn ($driver) => $driver->name, $type['result']->drivers())) }}</span>
                        @endif
                    </div>
                    @if($type['isOpenForPicks'])
                        <a href="{{ route('picks.create', ['round' => $round->id, 'type' => $type['id']]) }}" class="btn btn-primary">Pick</a>
                    @endif
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead class="thead">
                        <tr>
                            <th class="th">Name</th>
                            <th class="th">Picks</th>
                            <th class="th">Score</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800/80">
                        @forelse($type['picks'] as $pick)
                            <tr class="hover:bg-neutral-900/40">
                                <td class="td">{{ $pick->user->name }}</td>
                                <td class="td">
                                    @if ($pick->isOpen())
                                        <span class="text-neutral-400">...</span>
                                    @else
                                        {{ implode(', ', array_map(fn ($driver) => $driver->name, $pick->drivers())) }}
                                    @endif
                                </td>
                                <td class="td">{{ $pick->score }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="td text-center text-neutral-400">No picks</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>

    <script>
        const fmt = new Intl.DateTimeFormat('en-GB', {
            year: 'numeric', month: 'short', day: '2-digit',
            hour: '2-digit', minute: '2-digit', hour12: false,
        });
        [...document.getElementsByTagName('time')].forEach((time) => {
            const d = new Date(parseInt(time.getAttribute('datetime'), 10) * 1e3);
            time.textContent = fmt.format(d);
        });
    </script>
@endsection
