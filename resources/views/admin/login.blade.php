<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід — Pulse Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">

    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon"><i class="ti ti-news"></i></div>
            <h1>Pulse Admin</h1>
            <p>Увійдіть, щоб керувати новинами</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="admin@pulse.local"
                       required autofocus>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <label class="form-check">
                <input type="checkbox" name="remember">
                <span>Запамʼятати мене</span>
            </label>

            <button type="submit" class="btn btn--primary btn--block">
                Увійти <i class="ti ti-arrow-right"></i>
            </button>
        </form>
    </div>

</body>
</html>