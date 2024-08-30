<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>F1 Picks</title>

    <link rel="stylesheet" href="https://fonts.xz.style/serve/inter.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@exampledev/new.css@1.1.2/new.min.css">
</head>
<body>
<header style="display:flex">
    <h1 style="flex:1">🏁 F1 Picks</h1>
    <nav>
        <a href="{{ route('rounds.index') }}">Rounds</a> -
        @can('publish', App\Models\Result::class)
            <a href="{{ route('results.create') }}">Results</a> -
        @endcan
        @guest
            <a href="{{ route('login') }}">Login</a>
        @else
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                @csrf
            </form>
        @endguest
    </nav>
</header>

<main>
    @if(session()->has('success'))
        <blockquote>{{ session()->get('success') }}</blockquote>
    @endif

    @if(session()->has('errors'))
        {!! implode('', session()->get('errors')->all('<blockquote>:message</blockquote>')) !!}
    @endif

    @yield('content')
</main>

</body>
</html>
