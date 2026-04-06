@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $servicesIntro['eyebrow'] }}</span>
        <h1>{{ $locale === 'bn' ? 'আমাদের সেবাসমূহ' : 'Our Services' }}</h1>
        <p>{{ $servicesIntro['copy'] }}</p>
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="service-detail-grid">
            @foreach ($services as $service)
                <article class="service-detail-card" id="{{ $service['slug'] }}">
                    <div class="service-detail-media">
                        <img src="{{ $service['image'] ?? '/assets/images/service-card.svg' }}" alt="{{ $service['title'] }}" loading="lazy">
                    </div>
                    <div class="service-detail-head">
                        <!-- <span class="service-icon">{{ $service['icon'] }}</span> -->
                        <div>
                            <h2>{{ $service['title'] }}</h2>
                            <p>{{ $service['description'] }}</p>
                        </div>
                    </div>

                    <ul class="bullet-list">
                        @foreach ($service['deliverables'] as $deliverable)
                            <li>{{ $deliverable }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $locale === 'bn' ? 'আমাদের প্রক্রিয়া' : 'How We Work' }}</span>
            <h2>{{ $locale === 'bn' ? 'সুস্পষ্ট ধাপে এগোই' : 'A Clear Delivery Flow' }}</h2>
            <p>{{ $locale === 'bn' ? 'প্রত্যেক পর্যায়ে পরিকল্পনা, দৃশ্যমানতা এবং ফিডব্যাক রাখি।' : 'Each step keeps planning, visibility, and feedback close to the work.' }}</p>
        </div>

        <div class="process-grid">
            @foreach ($process as $step)
                <article class="process-card">
                    <span>{{ $step['step'] }}</span>
                    <h3>{{ $step['title'] }}</h3>
                    <p>{{ $step['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container cta-row">
        <div>
            <span class="section-label">{{ $locale === 'bn' ? 'পরবর্তী ধাপ' : 'Next Step' }}</span>
            <h2>{{ $locale === 'bn' ? 'আপনার দরকারি সেবাটি নিয়ে কথা বলি' : 'Let’s Scope the Right Service Mix' }}</h2>
            <p>{{ $locale === 'bn' ? 'কোন সেবা, কতটুকু স্কোপ, এবং কোন টাইমলাইনে কাজ হবে তা একসাথে ঠিক করি।' : 'We can shape the right scope, team, and timeline around the service you need.' }}</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn btn-primary">{{ $ui['quote_cta'] }}</a>
        </div>
    </div>
</section>
@endsection
