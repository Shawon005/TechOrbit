@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $blogIntro['eyebrow'] }}</span>
        <h1>{{ $locale === 'bn' ? 'ইনসাইট ও আর্টিকেল' : 'Insights & Articles' }}</h1>
        <p>{{ $blogIntro['copy'] }}</p>
    </div>
</section>

<section class="section-space">
    <div class="container blog-layout">
        <div class="blog-main">
            @foreach ($posts as $post)
                <article class="post-card post-card-wide">
                    <div class="post-topline">
                        <span>{{ $post['category'] }}</span>
                        <span>{{ $post['date'] }}</span>
                    </div>
                    <h2>{{ $post['title'] }}</h2>
                    <p>{{ $post['excerpt'] }}</p>
                    <div class="post-meta">
                        <span>{{ $post['read_time'] }}</span>
                        <a href="{{ route('site.contact', ['locale' => $locale]) }}">{{ $locale === 'bn' ? 'প্রজেক্ট নিয়ে কথা বলুন' : 'Talk About a Project' }}</a>
                    </div>
                </article>
            @endforeach
        </div>

        <aside class="blog-sidebar">
            <div class="sidebar-card">
                <h3>{{ $locale === 'bn' ? 'ক্যাটাগরি' : 'Categories' }}</h3>
                <div class="sidebar-list">
                    @foreach ($blogCategories as $category)
                        <span>{{ $category['name'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="sidebar-card">
                <h3>{{ $locale === 'bn' ? 'ফোকাস এরিয়া' : 'Focus Areas' }}</h3>
                <div class="sidebar-list">
                    <span>{{ $locale === 'bn' ? 'Laravel ও প্রোডাক্ট আর্কিটেকচার' : 'Laravel & product architecture' }}</span>
                    <span>{{ $locale === 'bn' ? 'MVP স্কোপিং' : 'MVP scoping' }}</span>
                    <span>{{ $locale === 'bn' ? 'UX ও ইন্টারনাল টুলস' : 'UX for internal tools' }}</span>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
