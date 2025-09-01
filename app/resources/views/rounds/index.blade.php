@extends('layout')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="mb-4 flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">Championship Rounds</h2>
                    <p class="text-sm text-neutral-400">Select a round to view picks, results, and your score.</p>
                </div>
                <form action="{{ route('rounds.index') }}" method="GET" class="inline-flex items-center gap-2">
                    <label for="year" class="text-sm text-neutral-300">Year</label>
                    <select name="year" id="year" onchange="this.form.submit()" class="form-select">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead class="thead">
                    <tr>
                        <th class="th w-30">Round</th>
                        <th class="th">Grand Prix</th>
                        <th class="th"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                    @forelse($rounds as $round)
                        <tr class="group hover:bg-neutral-900/40 cursor-pointer {{ isset($activeRoundId) && $activeRoundId === $round->id ? 'bg-neutral-900/70 ring-1 ring-red-700/50' : '' }}" onclick="window.location.href='{{ route('rounds.show', $round->id) }}'" role="link" aria-label="View {{ $round->name }}" tabindex="0" onkeydown="if(event.key==='Enter' || event.key===' '){ window.location.href='{{ route('rounds.show', $round->id) }}'; }">
                            <td class="td w-20 font-medium py-3">{{ $round->round }}</td>
                            <td class="td py-3">
                                {{ $round->name }}
                                @if(isset($activeRoundId) && $activeRoundId === $round->id)
                                    <span class="ml-2 align-middle text-xs font-semibold text-red-400">Current</span>
                                @endif
                            </td>
                            <td class="td py-3 text-right text-neutral-500 group-hover:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="inline-block h-4 w-4" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.47 4.47a.75.75 0 011.06 0l7 7a.75.75 0 010 1.06l-7 7a.75.75 0 11-1.06-1.06L14.94 12 8.47 5.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="td text-center text-neutral-400">No rounds</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                @if($rounds->hasPages())
                    <nav class="flex items-center justify-between" role="navigation" aria-label="Pagination Navigation">
                        <div>
                            @if(! $rounds->onFirstPage())
                                <a href="{{ $rounds->previousPageUrl() }}" class="btn-nav" aria-label="Previous page">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M15.53 4.47a.75.75 0 010 1.06L9.06 12l6.47 6.47a.75.75 0 11-1.06 1.06l-7-7a.75.75 0 010-1.06l7-7a.75.75 0 011.06 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Previous</span>
                                </a>
                            @endif
                        </div>
                        <div>
                            @if($rounds->hasMorePages())
                                <a href="{{ $rounds->nextPageUrl() }}" class="btn-nav" aria-label="Next page">
                                    <span>Next</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M8.47 4.47a.75.75 0 011.06 0l7 7a.75.75 0 010 1.06l-7 7a.75.75 0 11-1.06-1.06L14.94 12 8.47 5.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </nav>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-1">
            <div class="card">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">🏆 Scoreboard</h3>
                    <span class="badge badge-accent">{{ $year }}</span>
                </div>
                <ul class="divide-y divide-neutral-800/80">
                    @forelse($scores as $score)
                        <li class="group flex items-center justify-between py-3 cursor-pointer" onclick="window.location.href='{{ route('users.show', ['user' => $score->id, 'year' => $year]) }}'" role="link" aria-label="View {{ $score->name }} profile" tabindex="0" onkeydown="if(event.key==='Enter' || event.key===' '){ window.location.href='{{ route('users.show', ['user' => $score->id, 'year' => $year]) }}'; }">
                            <div class="min-w-0 flex items-center gap-3">
                                <span class="w-6 shrink-0 text-right text-sm text-neutral-400">{{ $loop->iteration }}</span>
                                <p class="truncate text-sm font-medium text-neutral-100">{{ $score->name }}</p>
                            </div>
                            <div class="ml-3 flex items-center gap-3">
                                <span class="rounded bg-neutral-800 px-2 py-0.5 text-xs text-neutral-200">{{ $score->score }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-neutral-500 group-hover:text-neutral-300" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.47 4.47a.75.75 0 011.06 0l7 7a.75.75 0 010 1.06l-7 7a.75.75 0 11-1.06-1.06L14.94 12 8.47 5.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-neutral-400">No users</li>
                    @endforelse
                </ul>
            </div>
        </aside>
    </div>
@endsection
