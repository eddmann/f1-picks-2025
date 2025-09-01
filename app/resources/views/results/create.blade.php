@extends('layout')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="card">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold tracking-tight">Round Results</h1>
                <span class="badge">Admin</span>
            </div>

            <form action="{{ route('results.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="round_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Round</label>
                        <select name="round_id" id="round_id" class="form-select w-full">
                            <option value="0">Pick a round</option>
                            @foreach($rounds as $round)
                                <option value="{{ $round->id }}" data-sprint="{{ $round->isSprintWeekend() ? 1 : 0 }}" {{ (old('round_id') == $round->id || (!old('round_id') && isset($activeRoundId) && $activeRoundId === $round->id)) ? 'selected' : '' }}>{{ $round->year }} - Round {{ $round->round }} ({{ $round->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Type</label>
                        <select name="type" id="type" class="form-select w-full">
                            <option value="">Pick a type</option>
                            @foreach(\App\Models\Type::cases() as $type)
                                <option value="{{ $type->value }}" @if(in_array($type->value, [\App\Models\Type::SPRINT_QUALIFYING->value, \App\Models\Type::SPRINT_RACE->value])) data-sprint-only="1" @endif {{ old('type') == $type->value ? 'selected' : '' }}>{{ $type->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="driver1_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 1</label>
                        <select name="driver1_id" id="driver1_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver1_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="driver2_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 2</label>
                        <select name="driver2_id" id="driver2_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver2_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="driver3_id" class="mb-1 block text-xs font-medium uppercase tracking-wide text-neutral-400">Driver 3</label>
                        <select name="driver3_id" id="driver3_id" class="driver form-select w-full">
                            <option value="0">Pick a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver3_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('rounds.index') }}" class="btn btn-muted">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        Save
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

            // Toggle Type options based on whether the selected round is a sprint weekend
            const roundSelect = document.getElementById('round_id');
            const typeSelect = document.getElementById('type');
            const updateTypeOptions = () => {
                const selectedRound = roundSelect.options[roundSelect.selectedIndex];
                const isSprintRound = selectedRound && selectedRound.dataset && selectedRound.dataset.sprint === '1';

                [...typeSelect.options].forEach((opt) => {
                    if (opt.value === '') { return; }
                    const isSprintOnly = opt.dataset && opt.dataset.sprintOnly === '1';
                    const shouldHide = isSprintOnly && !isSprintRound;
                    opt.disabled = shouldHide;
                    opt.hidden = shouldHide;
                });

                const selectedTypeOption = typeSelect.options[typeSelect.selectedIndex];
                if (selectedTypeOption && (selectedTypeOption.disabled || selectedTypeOption.hidden)) {
                    typeSelect.value = '';
                }
            };

            roundSelect.addEventListener('change', updateTypeOptions);
            updateTypeOptions();
        })();
    </script>
@endsection
