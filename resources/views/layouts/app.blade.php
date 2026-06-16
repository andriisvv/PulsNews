<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pulse — Новини')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <div class="page">

        {{-- ====== HEADER ====== --}}
        <header class="header">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <i class="ti ti-news"></i>
                </div>
                <span class="logo-text">Pulse</span>
            </a>

            <nav class="nav">
                <a href="{{ route('home') }}"
                   class="nav-pill {{ !request('category') ? 'nav-pill--active' : '' }}">
                    Усі
                </a>
                @foreach($categories ?? [] as $cat)
                    <a href="{{ route('home', ['category' => $cat]) }}"
                       class="nav-pill {{ request('category') === $cat ? 'nav-pill--active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </nav>

            <form action="{{ route('home') }}" method="GET" class="header-actions">
                <input type="text"
                       name="search"
                       placeholder="Пошук..."
                       value="{{ request('search') }}"
                       class="search-input">
                <button type="submit" class="icon-btn" aria-label="Пошук">
                    <i class="ti ti-search"></i>
                </button>
            </form>
        </header>

        {{-- ====== CONTENT ====== --}}
        @yield('content')

        {{-- ====== FOOTER ====== --}}
        <footer class="footer">
            <div class="footer-brand">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="ti ti-news"></i>
                    </div>
                    <span class="logo-text">Pulse</span>
                </div>
                <p class="footer-tagline">Актуальні новини щодня</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}">Про нас</a>
                <a href="{{ route('contacts') }}">Контакти</a>
                <a href="{{ route('advertising') }}">Реклама</a>
            </div>
            <div class="footer-copy">© {{ date('Y') }} Pulse. Усі права захищені.</div>
        </footer>

    </div>

</body>
</html>
