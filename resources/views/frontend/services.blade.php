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

<section class="section-space section-muted work-roadmap-section">
    <div class="container">
        <div class="work-roadmap">
            <div class="work-roadmap-intro">
                <span class="section-label">{{ $locale === 'bn' ? 'আমাদের প্রক্রিয়া' : 'How We Work' }}</span>
                <h2>{{ $locale === 'bn' ? 'পরিকল্পনা থেকে লঞ্চ পর্যন্ত একটি পরিষ্কার রোডম্যাপ' : 'A Clear Roadmap From Planning to Launch' }}</h2>
                <p>{{ $locale === 'bn' ? 'প্রতিটি পর্যায়ে আমরা লক্ষ্য, নকশা, ডেভেলপমেন্ট এবং সাপোর্টকে একই ধারার মধ্যে রাখি, যাতে কাজ থেমে না যায় এবং সিদ্ধান্তগুলো সবসময় পরিষ্কার থাকে।' : 'We keep strategy, design, development, and post-launch support connected in one visible flow so progress stays steady and decisions stay clear.' }}</p>

                <div class="work-roadmap-actions">
                    <a href="{{ route('site.software', ['locale' => $locale]) }}" class="software-btn software-btn-call">{{ $locale === 'bn' ? 'ডেমো দেখুন' : 'See Demo' }}</a>
                </div>
            </div>

            <div class="work-roadmap-stage" aria-label="{{ $locale === 'bn' ? 'কাজের ধাপসমূহ' : 'Process timeline' }}">
                <div class="work-roadmap-line" aria-hidden="true"></div>

                @foreach ($process as $step)
                    <article class="work-step-card work-step-{{ $loop->iteration }}">
                        <span class="work-step-node" aria-hidden="true"><span></span></span>
                        <!-- <span class="work-step-ghost" aria-hidden="true">{{ $loop->iteration }}</span> -->
                        <div class="work-step-body">
                            <span class="work-step-kicker">{{ $step['step'] }}</span>
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['copy'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
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
