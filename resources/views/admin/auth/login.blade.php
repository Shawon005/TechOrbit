<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
</head>
<body class="admin-login-body">
    <section class="admin-login-shell">
        <div class="admin-login-card">
            <div class="admin-login-brand">
                <strong><span>Tech</span>Orbit</strong>
                <p>Admin Panel</p>
            </div>

            <div class="admin-login-copy">
                <h1>Sign in to manage the website</h1>
                <p>Use the default admin account or your own admin user to access the protected panel.</p>
            </div>

            @if ($errors->any())
                <div class="admin-alert admin-alert-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="admin-login-form">
                @csrf

                <label class="admin-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', 'admin@techorbitit.com') }}" required>
                </label>

                <label class="admin-field">
                    <span>Password</span>
                    <input type="password" name="password" value="admin123" required>
                </label>

                <label class="admin-login-remember">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="admin-submit-btn admin-login-submit">Login to Admin</button>
            </form>

            <div class="admin-login-note">
                <strong>Default admin</strong>
                <span>`admin@techorbitit.com` / `admin123`</span>
            </div>
        </div>
    </section>
</body>
</html>
