@extends('layouts.app')

@section('content')
<style>
    @media (min-width: 700px) {
        .site-header{
            background-color: unset !important;
        }
        
    }
        @media (max-width: 700px) {
        .site-header{
            background-color:  rgba(13, 24, 39, 0.9); !important;
        }
        
    }
    
</style>
<section class="hero-section">
    <div class="hero-orb hero-orb-one"></div>
    <div class="hero-orb hero-orb-two"></div>
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="hero-slider" data-slider>
                @foreach ($heroSlides as $slide)
                    <article class="hero-slide {{ $loop->first ? 'active' : '' }}" data-slide>
                        <span class="eyebrow-pill">{{ $slide['badge'] }}</span>
                        <p class="hero-eyebrow">{{ $slide['eyebrow'] }}</p>
                        <h1>{{ $slide['title'] }}</h1>
                        <p class="hero-text">{{ $slide['subtitle'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="hero-actions">
                <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn btn-primary">{{ $ui['free_consultation'] }}</a>
                <a href="{{ route('site.portfolio', ['locale' => $locale]) }}" class="btn btn-secondary">{{ $ui['view_work'] }}</a>
            </div>

            <div class="hero-dots" data-slider-dots>
                @foreach ($heroSlides as $slide)
                    <button class="hero-dot {{ $loop->first ? 'active' : '' }}" type="button" data-slide-dot aria-label="Go to slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-panel-card hero-panel-primary">
                <p>{{ $locale === 'bn' ? 'ডিজিটাল সলিউশন' : 'Digital Solutions' }}</p>
                <strong>{{ $locale === 'bn' ? 'ওয়েব, মোবাইল, ERP' : 'Web, Mobile, ERP' }}</strong>
                <span>{{ $locale === 'bn' ? 'একই টিম থেকে স্ট্র্যাটেজি থেকে ডেলিভারি' : 'Strategy through delivery from one team' }}</span>
            </div>
            <div class="hero-panel-card">
                <p>{{ $locale === 'bn' ? 'ডেলিভারি স্টাইল' : 'Delivery Style' }}</p>
                <strong>{{ $locale === 'bn' ? 'স্বচ্ছ, দ্রুত, নির্ভরযোগ্য' : 'Clear, fast, dependable' }}</strong>
                <span>{{ $locale === 'bn' ? 'মাইলস্টোন, রিভিউ, এবং অবিচ্ছিন্ন যোগাযোগ' : 'Milestones, reviews, and steady communication' }}</span>
            </div>
            <div class="hero-stack">
                @foreach (array_slice($technologies, 0, 4) as $technology)
                    <span>{{ $technology }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="stats-strip">
    <div class="container stats-grid">
        @foreach ($stats as $stat)
            @php $numeric = (int) preg_replace('/\D+/', '', $stat['value']); @endphp
            <div class="stat-card">
                <strong data-counter="{{ $numeric }}" data-suffix="+">0+</strong>
                <span>{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </div>
</section>

@if (! empty($software))
<!-- <section class="section-space software-showcase" id="our-software">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $ui['our_software'] }}</span>
            <h2>{{ $ui['our_software_title'] }}</h2>
            <p>{{ $ui['our_software_copy'] }}</p>
        </div>

        <div class="software-stack">
            @foreach ($software as $product)
                <article class="software-card {{ $loop->even ? 'software-card-reverse' : '' }}">
                    <div class="software-copy-block">
                        <span class="section-label software-badge">{{ $product['badge'] }}</span>
                        <h3>{{ $product['title'] }}</h3>
                        <p class="software-lead">{{ $product['excerpt'] }}</p>
                        <p>{{ $product['description'] }}</p>

                        <div class="software-actions">
                            <a href="tel:{{ preg_replace('/\D+/', '', $company['phone']) }}" class="software-btn software-btn-call">{{ $ui['call_now'] }}</a>
                            @if (! empty($product['video_url']))
                                <a href="{{ $product['video_url'] }}" class="software-btn software-btn-demo" target="_blank" rel="noreferrer">{{ $ui['watch_demo'] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="software-visual">
                        <div class="software-orbit"></div>
                        <div class="software-media">
                            <img src="{{ $product['image'] ?? '/assets/images/service-card.svg' }}" alt="{{ $product['title'] }}" loading="lazy">
                        </div>

                        <div class="software-chip software-chip-top">
                            <strong>{{ $product['badge'] }}</strong>
                            <span>{{ $ui['our_software'] }}</span>
                        </div>

                        @if (! empty($product['video_url']))
                            <a href="{{ $product['video_url'] }}" class="software-chip software-chip-bottom" target="_blank" rel="noreferrer">
                                <strong>{{ $ui['watch_demo'] }}</strong>
                                <span>{{ $locale === 'bn' ? 'ভিডিও লিংক যুক্ত আছে' : 'Video link ready' }}</span>
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="section-link-row mt-3">
            <a href="{{ route('site.software', ['locale' => $locale]) }}" class="section-link">{{ $locale === 'bn' ? 'সব সফটওয়্যার দেখুন' : 'View All Software' }}</a>
        </div>
    </div>
</section> -->
@endif

<section class="section-space">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $servicesIntro['eyebrow'] }}</span>
            <h2>{{ $servicesIntro['title'] }}</h2>
            <p>{{ $servicesIntro['copy'] }}</p>
        </div>

        <div class="service-grid">
            @foreach (array_slice($services, 0, 6) as $service)
                <article class="service-card">
                    <div class="service-media">
                        <img src="{{ $service['image'] ?? '/assets/images/service-card.svg' }}" alt="{{ $service['title'] }}" loading="lazy">
                    </div>
                    <!-- <span class="service-icon">{{ $service['icon'] }}</span> -->
                    <h3>{{ $service['title'] }}</h3>
                    <p>{{ $service['excerpt'] }}</p>
                    <a href="{{ route('site.services', ['locale' => $locale]) }}#{{ $service['slug'] }}">{{ $ui['learn_more'] }}</a>
                </article>
            @endforeach
        </div>

        <div class="section-link-row mt-3">
            <a href="{{ route('site.services', ['locale' => $locale]) }}" class="section-link">{{ $ui['view_all_services'] }}</a>
        </div>
    </div>
</section>

<section class="section-space subscription-section">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $subscriptionOffers['eyebrow'] }}</span>
            <h2>{{ $subscriptionOffers['title'] }}</h2>
            <p>{{ $subscriptionOffers['copy'] }}</p>
        </div>

        <div class="subscription-note">
            <strong>{{ $locale === 'bn' ? 'মাসিক মডেল' : 'Monthly Model' }}</strong>
            <p>{{ $subscriptionOffers['note'] }}</p>
        </div>

        <div class="pricing-grid">
            @foreach ($subscriptionOffers['plans'] as $plan)
                <article class="pricing-card {{ !empty($plan['featured']) ? 'pricing-card-featured' : '' }}">
                    <div class="pricing-card-top">
                        <span class="pricing-badge">{{ $plan['badge'] }}</span>
                        <h3>{{ $plan['name'] }}</h3>
                        <p>{{ $plan['summary'] }}</p>
                    </div>

                    <div class="pricing-price-row">
                        <strong>{{ $plan['price'] }}</strong>
                        <div>
                            <span>{{ $plan['currency'] }}</span>
                            <small>{{ $plan['period'] }}</small>
                        </div>
                    </div>

                    <ul class="pricing-list">
                        @foreach ($plan['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="pricing-actions">
                        <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn {{ !empty($plan['featured']) ? 'btn-primary' : 'btn-dark' }}">{{ $locale === 'bn' ? 'প্ল্যানটি নিন' : 'Choose Plan' }}</a>
                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $company['whatsapp']) }}" class="pricing-link" target="_blank" rel="noreferrer">{{ $ui['whatsapp_us'] }}</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container split-grid">
        <div class="section-heading left-aligned">
            <span class="section-label">{{ $whyChooseUs['eyebrow'] }}</span>
            <h2>{{ $whyChooseUs['title'] }}</h2>
            <p>{{ $whyChooseUs['copy'] }}</p>

            <div class="feature-list">
                @foreach ($whyChooseUs['points'] as $point)
                    <article class="feature-item">
                        <div class="feature-badge"></div>
                        <div>
                            <h3>{{ $point['title'] }}</h3>
                            <p>{{ $point['description'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="insight-panel">
            <span class="section-label">{{ $locale === 'bn' ? 'সহযোগিতা' : 'Collaboration' }}</span>
            <h3>{{ $whyChooseUs['panel']['title'] }}</h3>
            <div class="insight-chart">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <ul class="bullet-list">
                @foreach ($whyChooseUs['panel']['items'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $projectIntro['eyebrow'] }}</span>
            <h2>{{ $projectIntro['title'] }}</h2>
            <p>{{ $projectIntro['copy'] }}</p>
        </div>

        <div class="filter-row" data-filter-group="featured-projects">
            @foreach ($projectCategories as $category)
                <button class="filter-pill {{ $loop->first ? 'active' : '' }}" type="button" data-filter="{{ $category['key'] }}">{{ $category['label'] }}</button>
            @endforeach
        </div>

        <div class="project-grid">
            @foreach (array_slice($projects, 0, 6) as $project)
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

        <div class="section-link-row mt-3">
            <a href="{{ route('site.portfolio', ['locale' => $locale]) }}" class="section-link">{{ $ui['view_all_projects'] }}</a>
        </div>
    </div>
</section>

<section class="section-space testimonials-section">
    <div class="container">
        <div class="section-heading section-heading-light">
            <span class="section-label">{{ $locale === 'bn' ? 'মতামত' : 'Testimonials' }}</span>
            <h2>{{ $locale === 'bn' ? 'ক্লায়েন্টরা যা বলছেন' : 'What Our Clients Say' }}</h2>
            <p>{{ $locale === 'bn' ? 'বিশ্বাস, যোগাযোগ এবং ফলাফল নিয়ে গড়ে ওঠা দীর্ঘমেয়াদি সম্পর্ক।' : 'Long-term relationships built on trust, communication, and useful outcomes.' }}</p>
        </div>

        <div class="testimonial-grid">
            @foreach ($testimonials as $testimonial)
                <article class="testimonial-card">
                    <span class="stars">★★★★★</span>
                    <p>{{ $testimonial['message'] }}</p>
                    <div class="testimonial-author">
                        <div class="avatar">{{ strtoupper(substr($testimonial['name'], 0, 1)) }}</div>
                        <div>
                            <strong>{{ $testimonial['name'] }}</strong>
                            <span>{{ $testimonial['role'] }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="tech-marquee">
    <div class="marquee-track">
        @foreach (array_merge($technologies, $technologies) as $technology)
            <span>{{ $technology }}</span>
        @endforeach
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $blogIntro['eyebrow'] }}</span>
            <h2>{{ $blogIntro['title'] }}</h2>
            <p>{{ $blogIntro['copy'] }}</p>
        </div>

        <div class="blog-grid">
            @foreach ($posts as $post)
                <article class="post-card">
                    <div class="post-topline">
                        <span>{{ $post['category'] }}</span>
                        <span>{{ $post['date'] }}</span>
                    </div>
                    <h3>{{ $post['title'] }}</h3>
                    <p>{{ $post['excerpt'] }}</p>
                    <div class="post-meta">
                        <span>{{ $post['read_time'] }}</span>
                        <a href="{{ route('site.blog', ['locale' => $locale]) }}">{{ $ui['learn_more'] }}</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="section-link-row mt-3">
            <a href="{{ route('site.blog', ['locale' => $locale]) }}" class="section-link">{{ $ui['view_all_articles'] }}</a>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container cta-row">
        <div>
            <span class="section-label">{{ $locale === 'bn' ? 'চলুন শুরু করি' : 'Let’s Build' }}</span>
            <h2>{{ $ui['ready_title'] }}</h2>
            <p>{{ $ui['ready_copy'] }}</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn btn-primary">{{ $ui['book_call'] }}</a>
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $company['whatsapp']) }}" class="btn btn-secondary" target="_blank" rel="noreferrer">{{ $ui['whatsapp_us'] }}</a>
        </div>
    </div>
</section>
@endsection
