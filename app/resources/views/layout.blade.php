<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F1 Picks</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-neutral-950 text-neutral-100 antialiased">
<header class="sticky top-0 z-40 border-b border-neutral-800/80 bg-neutral-950/80 backdrop-blur supports-[backdrop-filter]:bg-neutral-950/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between">
            <a href="{{ route('rounds.index') }}" class="inline-flex items-center gap-2 font-semibold tracking-tight">
                <img src="{{ inline_public_asset_uri('logo.png') }}" alt="" class="h-8 w-auto" />
                <span class="sr-only">F1 Picks</span>
            </a>
            <button id="mobile-nav-toggle" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-neutral-300 hover:bg-neutral-800/50 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Toggle navigation">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <nav id="primary-nav" class="hidden md:flex items-center gap-6">
                <a href="{{ route('rounds.index') }}" class="text-sm font-medium text-neutral-300 hover:text-white">Rounds</a>
                @can('publish', App\Models\Result::class)
                    <a href="{{ route('drivers.index') }}" class="text-sm font-medium text-neutral-300 hover:text-white">Drivers</a>
                    <a href="{{ route('results.create') }}" class="text-sm font-medium text-neutral-300 hover:text-white">Results</a>
                @endcan
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500">Login</a>
                @else
                    <span class="text-sm text-neutral-400">{{ auth()->user()->email }}</span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden md:block">
                        @csrf
                        <button class="inline-flex items-center rounded-md bg-neutral-800 px-3 py-1.5 text-sm font-semibold text-neutral-100 hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-red-500">Logout</button>
                    </form>
                @endguest
            </nav>
        </div>
        <nav id="mobile-nav" class="md:hidden hidden pb-3">
            <div class="mt-2 grid gap-2">
                <a href="{{ route('rounds.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800/60 hover:text-white">Rounds</a>
                @can('publish', App\Models\Result::class)
                    <a href="{{ route('drivers.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800/60 hover:text-white">Drivers</a>
                    <a href="{{ route('results.create') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800/60 hover:text-white">Results</a>
                @endcan
                @guest
                    <a href="{{ route('login') }}" class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow hover:bg-red-500">Login</a>
                @else
                    <div class="px-3 py-2 text-sm text-neutral-400">{{ auth()->user()->email }}</div>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button class="w-full rounded-md bg-neutral-800 px-3 py-2 text-sm font-semibold text-neutral-100 hover:bg-neutral-700">Logout</button>
                    </form>
                @endguest
            </div>
        </nav>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    @if(session()->has('success'))
        <div class="mb-4 rounded-md border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">{{ session()->get('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 space-y-2 rounded-md border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            @foreach ($errors->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>

<footer class="mt-8 border-t border-neutral-800/80 py-6 text-center text-xs text-neutral-500">
    <p>Formula 1 data and names are trademarks of their respective owners.</p>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</footer>

</body>
</html>
