@extends('layout')

@section('content')
    <div class="mb-4 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold tracking-tight">{{ $user->name }}</h2>
            <p class="text-sm text-neutral-400">Picks and scores</p>
            <p class="mt-1 text-sm text-neutral-300">Total {{ $year }} score:
                <span class="ml-2 rounded bg-neutral-800 px-2 py-0.5 text-xs text-neutral-200">{{ $totalScore }}</span>
            </p>
        </div>
        <form action="{{ route('users.show', $user->id) }}" method="GET" class="inline-flex items-center gap-2">
            <label for="year" class="text-sm text-neutral-300">Year</label>
            <select name="year" id="year" onchange="this.form.submit()" class="form-select">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($picks as $round => $picksForRound)
            <section class="card">
                <h3 class="mb-3 text-lg font-semibold">{{ $round }}</h3>
                <div class="table-wrap">
                    <table class="table">
                        <thead class="thead">
                        <tr>
                            <th class="th">Type</th>
                            <th class="th">Picks</th>
                            <th class="th">Score</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800/80">
                        @foreach($picksForRound as $pick)
                            <tr class="hover:bg-neutral-900/40">
                                <td class="td">{{ $pick->type->title() }}</td>
                                <td class="td">
                                    @if ($pick->isOpen())
                                        <span class="text-neutral-400">...</span>
                                    @else
                                        {{ implode(', ', array_map(fn ($driver) => $driver->name, $pick->drivers())) }}
                                    @endif
                                </td>
                                <td class="td">{{ $pick->score }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>
@endsection
