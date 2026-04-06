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
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <strong><span>Tech</span>Orbit</strong>
                <p>Admin Panel</p>
            </div>

            @foreach ($adminMenu as $group)
                <div class="admin-menu-group">
                    <span>{{ $group['section'] }}</span>
                    @foreach ($group['items'] as $item)
                        <a href="{{ route($item['route']) }}" class="admin-menu-item {{ $item['active'] ? 'active' : '' }}">
                            <span>{{ $item['label'] }}</span>
                            @if ($item['badge'])
                                <em>{{ $item['badge'] }}</em>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <h1>{{ $sectionTitle }}</h1>
                    <p>{{ $sectionDescription }}</p>
                </div>

                <div class="admin-topbar-actions">
                    <a href="{{ route('site.home', ['locale' => 'en']) }}" class="admin-ghost-link">View Site</a>
                    <a href="{{ route('admin.inquiries') }}" class="admin-notification-chip">
                        <span>Notifications</span>
                        <strong>{{ $newInquiryCount }}</strong>
                    </a>
                    <div class="admin-user-chip">
                        <strong>{{ auth()->user()?->name }}</strong>
                        <span>{{ auth()->user()?->email }}</span>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-ghost-link admin-logout-btn">Logout</button>
                    </form>
                    <div class="admin-avatar">AD</div>
                </div>
            </header>

            @yield('content')
        </main>
    </div>
</body>
</html>
