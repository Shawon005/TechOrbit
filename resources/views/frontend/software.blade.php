@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $ui['our_software'] }}</span>
        <h1>{{ $ui['our_software_title'] }}</h1>
        <p>{{ $locale === 'bn' ? 'আপনার সফটওয়্যার সলিউশন, ডেমো ভিডিও, প্রোডাক্ট ভিজ্যুয়াল এবং ব্যবসা-কেন্দ্রিক বর্ণনা একসাথে দেখার জন্য এই পেজটি ব্যবহার করুন।' : 'Browse your software portfolio in one place with product visuals, demo links, and business-focused descriptions that make each solution easy to understand.' }}</p>
    </div>
</section>

<section class="section-space software-showcase">
    <div class="container">
        @if (! empty($software))
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
        @else
            <div class="highlight-card">
                <div>
                    <span class="section-label">{{ $ui['our_software'] }}</span>
                    <h2>{{ $locale === 'bn' ? 'এখানে শিগগিরই সফটওয়্যার যোগ হবে' : 'Software items will appear here soon' }}</h2>
                    <p>{{ $locale === 'bn' ? 'অ্যাডমিন প্যানেল থেকে সফটওয়্যার তথ্য, ছবি এবং ভিডিও লিংক যোগ করলে এই পেজে সেগুলো দেখাবে।' : 'As soon as you add software details, images, and demo links from the admin panel, they will appear on this page.' }}</p>
                </div>
            </div>
        @endif
    </div>
</section>

<section class="cta-band">
    <div class="container cta-row">
        <div>
            <span class="section-label">{{ $locale === 'bn' ? 'পরবর্তী ধাপ' : 'Next Step' }}</span>
            <h2>{{ $locale === 'bn' ? 'আপনার সফটওয়্যার নিয়ে কথা বলি' : 'Let’s Talk About Your Software' }}</h2>
            <p>{{ $locale === 'bn' ? 'আপনি চাইলে এই পেজে প্রতিটি সফটওয়্যারের জন্য আলাদা ছবি, ডেমো ভিডিও এবং বিক্রয়মুখী কপি দেখাতে পারেন।' : 'You can use this page to present each software product with custom visuals, demo videos, and sales-ready messaging.' }}</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('site.contact', ['locale' => $locale]) }}" class="btn btn-primary">{{ $ui['quote_cta'] }}</a>
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $company['whatsapp']) }}" class="btn btn-secondary" target="_blank" rel="noreferrer">{{ $ui['whatsapp_us'] }}</a>
        </div>
    </div>
</section>
@endsection
