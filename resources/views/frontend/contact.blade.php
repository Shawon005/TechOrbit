@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <span class="section-label">{{ $contact['eyebrow'] }}</span>
        <h1>{{ $contact['title'] }}</h1>
        <p>{{ $contact['copy'] }}</p>
    </div>
</section>

<section class="section-space">
    <div class="container contact-grid">
        <div class="contact-panel">
            <h2>{{ $contact['info_title'] }}</h2>
            <div class="contact-items">
                <article>
                    <span>{{ $locale === 'bn' ? 'ঠিকানা' : 'Address' }}</span>
                    <p>{{ $company['address'] }}</p>
                </article>
                <article>
                    <span>{{ $locale === 'bn' ? 'ফোন' : 'Phone' }}</span>
                    <p>{{ $company['phone'] }}</p>
                </article>
                <article>
                    <span>{{ $locale === 'bn' ? 'ইমেইল' : 'Email' }}</span>
                    <p>{{ $company['email'] }}</p>
                </article>
                <article>
                    <span>{{ $locale === 'bn' ? 'অফিস সময়' : 'Office Hours' }}</span>
                    <p>{{ $contact['office_hours'] }}</p>
                </article>
            </div>

            <div class="map-card">
                <strong>{{ $contact['map_title'] }}</strong>
                <p>{{ $company['location'] }}</p>
            </div>
        </div>

        <div class="form-panel">
            <h2>{{ $contact['form_title'] }}</h2>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    {{ $locale === 'bn' ? 'ফর্মটি সাবমিট করার আগে অনুগ্রহ করে সব প্রয়োজনীয় ঘর পূরণ করুন।' : 'Please complete the required fields before submitting the form.' }}
                </div>
            @endif

            <form action="{{ route('site.contact.store', ['locale' => $locale]) }}" method="POST" class="contact-form">
                @csrf
                <div class="form-row">
                    <label>
                        <span>{{ $locale === 'bn' ? 'আপনার নাম *' : 'Your Name *' }}</span>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </label>
                    <label>
                        <span>{{ $locale === 'bn' ? 'ইমেইল *' : 'Email *' }}</span>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </label>
                </div>

                <div class="form-row">
                    <label>
                        <span>{{ $locale === 'bn' ? 'ফোন' : 'Phone' }}</span>
                        <input type="text" name="phone" value="{{ old('phone') }}">
                    </label>
                    <label>
                        <span>{{ $locale === 'bn' ? 'কোম্পানি' : 'Company' }}</span>
                        <input type="text" name="company" value="{{ old('company') }}">
                    </label>
                </div>

                <div class="form-row">
                    <label>
                        <span>{{ $locale === 'bn' ? 'প্রয়োজনীয় সেবা' : 'Service Needed' }}</span>
                        <select name="service">
                            <option value="">{{ $locale === 'bn' ? 'সিলেক্ট করুন' : 'Select service' }}</option>
                            @foreach ($contact['services'] as $service)
                                <option value="{{ $service['value'] }}" @selected(old('service') === $service['value'])>{{ $service['label'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span>{{ $locale === 'bn' ? 'বাজেট' : 'Budget Range' }}</span>
                        <select name="budget">
                            <option value="">{{ $locale === 'bn' ? 'সিলেক্ট করুন' : 'Select budget' }}</option>
                            @foreach ($contact['budgets'] as $budget)
                                <option value="{{ $budget['value'] }}" @selected(old('budget') === $budget['value'])>{{ $budget['label'] }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <label>
                    <span>{{ $locale === 'bn' ? 'বার্তা *' : 'Message *' }}</span>
                    <textarea name="message" rows="6" required>{{ old('message') }}</textarea>
                </label>

                <button type="submit" class="btn btn-primary">{{ $locale === 'bn' ? 'বার্তা পাঠান' : 'Send Message' }}</button>
            </form>
        </div>
    </div>
</section>

<section class="section-space section-muted">
    <div class="container">
        <div class="section-heading">
            <span class="section-label">{{ $locale === 'bn' ? 'জিজ্ঞাসা' : 'FAQ' }}</span>
            <h2>{{ $locale === 'bn' ? 'প্রায় জিজ্ঞাসিত প্রশ্ন' : 'Frequently Asked Questions' }}</h2>
        </div>

        <div class="faq-list">
            @foreach ($faq as $item)
                <article class="faq-item">
                    <button type="button" class="faq-toggle" data-faq-toggle>
                        <span>{{ $item['question'] }}</span>
                        <strong>+</strong>
                    </button>
                    <div class="faq-answer">
                        <p>{{ $item['answer'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
