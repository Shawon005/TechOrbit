@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $projectIntro['eyebrow'] }}</span>
        <h1>{{ $locale === 'bn' ? 'আমাদের পোর্টফোলিও' : 'Our Portfolio' }}</h1>
        <p>{{ $projectIntro['copy'] }}</p>
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="filter-row" data-filter-group="portfolio-page">
            @foreach ($projectCategories as $category)
                <button class="filter-pill {{ $loop->first ? 'active' : '' }}" type="button" data-filter="{{ $category['key'] }}">{{ $category['label'] }}</button>
            @endforeach
        </div>

        <div class="project-grid project-grid-large">
            @foreach ($projects as $project)
                @php $projectUrl = $project['link'] ?? null; @endphp
                <article class="project-card" data-filter-item="{{ $project['category'] }}">
                    @if ($projectUrl)
                        <a href="{{ $projectUrl }}" class="project-card-link" aria-label="Open {{ $project['title'] }}"></a>
                    @endif
                    <div class="project-visual gradient-{{ $project['gradient'] }}">
                        <img src="{{ $project['image'] ?? '/assets/images/project-card.svg' }}" alt="{{ $project['title'] }}" loading="lazy">
                        <span>{{ strtoupper($project['category']) }}</span>
                    </div>
                    <div class="project-body">
                        <div class="project-meta">
                            <span>{{ $project['client'] }}</span>
                            <span>{{ $project['year'] }}</span>
                        </div>
                        <h3>{{ $project['title'] }}</h3>
                        <p>{{ $project['summary'] }}</p>
                        <strong>{{ $project['tech'] }}</strong>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container highlight-card">
        <div>
            <span class="section-label">{{ $locale === 'bn' ? 'ফোকাস' : 'Focus' }}</span>
            <h2>{{ $locale === 'bn' ? 'আমরা শুধু সুন্দর UI নয়, কার্যকর সফটওয়্যার ডেলিভার করি' : 'We Deliver More Than Good Screens' }}</h2>
            <p>{{ $locale === 'bn' ? 'প্রতিটি প্রজেক্টে আমরা ইউজার এক্সপেরিয়েন্স, ব্যবসায়িক ফলাফল এবং দীর্ঘমেয়াদি রক্ষণাবেক্ষণকে একসাথে দেখি।' : 'Every project is shaped around user experience, business value, and a codebase that remains practical to maintain.' }}</p>
        </div>
        <div class="highlight-metrics">
            @foreach ($stats as $stat)
                <article>
                    <strong>{{ $stat['value'] }}</strong>
                    <span>{{ $stat['label'] }}</span>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
