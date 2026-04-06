@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $about['eyebrow'] }}</span>
        <h1>{{ $about['title'] }}</h1>
        <p>{{ $about['story'] }}</p>
    </div>
</section>

<section class="section-space">
    <div class="container about-grid">
        <article class="mission-card">
            <span class="section-label">{{ $locale === 'bn' ? 'মিশন' : 'Mission' }}</span>
            <h2>{{ $about['mission'] }}</h2>
        </article>
        <article class="mission-card">
            <span class="section-label">{{ $locale === 'bn' ? 'ভিশন' : 'Vision' }}</span>
            <h2>{{ $about['vision'] }}</h2>
        </article>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $locale === 'bn' ? 'মূল্যবোধ' : 'Core Values' }}</span>
            <h2>{{ $locale === 'bn' ? 'আমাদের কাজের ভিত' : 'How We Work Together' }}</h2>
        </div>

        <div class="value-grid">
            @foreach ($values as $value)
                <article class="value-card">
                    <h3>{{ $value['title'] }}</h3>
                    <p>{{ $value['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $locale === 'bn' ? 'টিম' : 'Team' }}</span>
            <h2>{{ $locale === 'bn' ? 'যারা এই কাজগুলো বাস্তবায়ন করে' : 'The People Behind the Delivery' }}</h2>
        </div>

        <div class="team-grid">
            @foreach ($team as $member)
                <article class="team-card">
                    <div class="team-photo">
                        <img src="{{ $member['image'] ?? '/assets/images/team-card.svg' }}" alt="{{ $member['name'] }}" loading="lazy">
                    </div>
                    <!-- <div class="team-avatar">{{ $member['initials'] }}</div> -->
                    <h3>{{ $member['name'] }}</h3>
                    <p>{{ $member['role'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $locale === 'bn' ? 'মাইলস্টোন' : 'Milestones' }}</span>
            <h2>{{ $locale === 'bn' ? 'যাত্রাপথের কয়েকটি ধাপ' : 'A Few Moments Along the Way' }}</h2>
        </div>

        <div class="timeline">
            @foreach ($milestones as $milestone)
                <article class="timeline-item">
                    <span>{{ $milestone['year'] }}</span>
                    <div>
                        <h3>{{ $milestone['title'] }}</h3>
                        <p>{{ $milestone['copy'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
