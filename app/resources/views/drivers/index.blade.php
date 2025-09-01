@extends('layout')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="card">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold tracking-tight">Drivers</h1>
                <span class="badge">Admin</span>
            </div>

            <form action="{{ route('drivers.update') }}" method="POST" class="space-y-4">
                @csrf

                <div class="divide-y divide-neutral-800 rounded-md border border-neutral-800">
                    @foreach($drivers as $driver)
                        <div class="flex items-center justify-between p-3">
                            <div class="text-sm font-medium">{{ $driver->name }}</div>
                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm">
                                <input type="hidden" name="drivers[{{ $driver->id }}][id]" value="{{ $driver->id }}">
                                <input type="checkbox" name="drivers[{{ $driver->id }}][active]" value="1" class="h-4 w-4 rounded border-neutral-700 bg-neutral-900 text-red-600 focus:ring-red-600" {{ $driver->active ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
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
@endsection


