<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Адмін-панель') — Pulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">

    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <div class="logo-icon"><i class="ti ti-news"></i></div>
            <span>Pulse Admin</span>
        </a>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ti ti-dashboard"></i> Дашборд
            </a>
            <a href="{{ route('admin.news.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="ti ti-news"></i> Новини
            </a>
            <a href="{{ route('admin.messages.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="ti ti-mail"></i> Повідомлення
            </a>
            <a href="{{ route('admin.news.create') }}" class="sidebar-link">
                <i class="ti ti-plus"></i> Додати новину
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="ti ti-external-link"></i> Відкрити сайт
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="sidebar-link sidebar-link--btn">
                    <i class="ti ti-logout"></i> Вийти
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert--success">
                <i class="ti ti-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert--error">
                <i class="ti ti-alert-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>