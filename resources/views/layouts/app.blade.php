<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/site.webmanifest') }}">
</head>
<body class="site-body {{ $locale === 'bn' ? 'locale-bn' : 'locale-en' }}">
@php
    $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
    $routeParams = request()->route()?->parameters() ?? [];
    $routeParams['locale'] = $alternateLocale;
    $switchUrl = str_starts_with((string) $currentRoute, 'site.')
        ? route($currentRoute, $routeParams)
        : route('site.home', ['locale' => $alternateLocale]);
    $whatsAppMessage = rawurlencode($locale === 'bn'
        ? 'হ্যালো TechOrbit IT, আমি আপনাদের সার্ভিস সম্পর্কে জানতে চাই।'
        : 'Hello TechOrbit IT, I need information about your services.');
@endphp

<div class="site-shell">
    <header class="site-header js-site-header">
        <div class="container header-row">
            <a href="{{ route('site.home', ['locale' => $locale]) }}" class="brand-mark" style="display: flex; align-items: center;">
                <img src="{{ asset('assets/images/Tech_Orbit_IT_logo.png') }}" alt="" height="45" loading="lazy" style="margin-right: 0.2rem;">
                <span>Tech Orbit IT</span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                
                @foreach ($navigation as $item)
                    @php
                        $itemUrl = route($item['route'], ['locale' => $locale]).(! empty($item['fragment']) ? '#'.$item['fragment'] : '');
                        $isActive = empty($item['fragment']) && request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ $itemUrl }}" class="{{ $isActive ? 'active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="header-actions">
                <a href="{{ $switchUrl }}" class="lang-pill">{{ strtoupper($alternateLocale) }} | {{ $alternateLocale === 'bn' ? 'বাংলা' : 'EN' }}</a>
                <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn btn-primary btn-small">{{ $ui['quote_cta'] }}</a>
                <button class="nav-toggle js-nav-toggle" type="button" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        <div class="mobile-nav js-mobile-nav">
            <div class="container mobile-nav-links">
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route'], ['locale' => $locale]).(! empty($item['fragment']) ? '#'.$item['fragment'] : '') }}">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ $switchUrl }}">{{ strtoupper($alternateLocale) }} | {{ $alternateLocale === 'bn' ? 'বাংলা' : 'EN' }}</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a href="{{ route('site.home', ['locale' => $locale]) }}" class="brand-mark footer-brand">
                    <span>Tech</span>Orbit IT
                </a>
                <p class="footer-copy">{{ $company['footer_about'] }}</p>
                <div class="social-list">
                    @foreach ($company['socials'] as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noreferrer">{{ $social['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3>{{ $locale === 'bn' ? 'দ্রুত লিংক' : 'Quick Links' }}</h3>
                <div class="footer-links">
                    @foreach ($navigation as $item)
                        <a href="{{ route($item['route'], ['locale' => $locale]).(! empty($item['fragment']) ? '#'.$item['fragment'] : '') }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3>{{ $locale === 'bn' ? 'মূল সেবা' : 'Core Services' }}</h3>
                <div class="footer-links">
                    @foreach (array_slice($services, 0, 5) as $service)
                        <a href="{{ route('site.services', ['locale' => $locale]) }}#{{ $service['slug'] }}">{{ $service['title'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3>{{ $locale === 'bn' ? 'যোগাযোগ' : 'Contact' }}</h3>
                <div class="footer-contact">
                    <p>{{ $company['address'] }}</p>
                    <p>{{ $company['phone'] }}</p>
                    <p>{{ $company['email'] }}</p>
                    <p>{{ $company['location'] }}</p>
                </div>
            </div>
        </div>

        <div class="container footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $company['name'] }}. {{ $locale === 'bn' ? 'সর্বস্বত্ব সংরক্ষিত।' : 'All rights reserved.' }}</p>
            <p>{{ $locale === 'bn' ? 'Privacy Policy · Terms of Service' : 'Privacy Policy · Terms of Service' }}</p>
        </div>
    </footer>
</div>

<a
    href="https://wa.me/{{ preg_replace('/\D+/', '', $company['whatsapp']) }}?text={{ $whatsAppMessage }}"
    class="whatsapp-float"
    target="_blank"
    rel="noreferrer"
>
    {{ $ui['whatsapp_us'] }}
</a>

<script src="{{ asset('assets/site.js') }}" defer></script>
</body>
</html>
