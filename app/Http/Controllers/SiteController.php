<?php

namespace App\Http\Controllers;

use App\Support\TechOrbitContentStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function __construct(private readonly TechOrbitContentStore $store)
    {
    }

    public function home(string $locale): View
    {
        return view('frontend.home', $this->pageData($locale, 'home'));
    }

    public function services(string $locale): View
    {
        return view('frontend.services', $this->pageData($locale, 'services'));
    }

    public function portfolio(string $locale): View
    {
        return view('frontend.portfolio', $this->pageData($locale, 'portfolio'));
    }

    public function about(string $locale): View
    {
        return view('frontend.about', $this->pageData($locale, 'about'));
    }

    public function blog(string $locale): View
    {
        return view('frontend.blog', $this->pageData($locale, 'blog'));
    }

    public function contact(string $locale): View
    {
        return view('frontend.contact', $this->pageData($locale, 'contact'));
    }

    public function contactStore(Request $request, string $locale): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:120'],
            'service' => ['nullable', 'string', 'max:120'],
            'budget' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $nameParts = preg_split('/\s+/', trim($validated['name'])) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => strtoupper(substr($part, 0, 1)))
            ->implode('');

        $this->store->prepend('admin.inquiries', [
            'initials' => $initials !== '' ? $initials : 'NA',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?: '-',
            'company' => $validated['company'] ?: '-',
            'service' => $validated['service'] ?: 'General Inquiry',
            'budget' => $validated['budget'] ?: 'Not specified',
            'message' => $validated['message'],
            'date' => Carbon::now()->format('M d, Y h:i A'),
            'status' => 'New',
            'tone' => 'new',
        ]);

        $message = $locale === 'bn'
            ? 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে। আমরা খুব শিগগিরই আপনার সঙ্গে যোগাযোগ করব।'
            : 'Your message has been sent successfully. We will get back to you very soon.';

        return back()->with('success', $message);
    }

    private function pageData(string $locale, string $pageKey): array
    {
        $site = $this->localizedCatalog($locale);

        return array_merge($site, [
            'locale' => $locale,
            'alternateLocale' => $locale === 'en' ? 'bn' : 'en',
            'pageKey' => $pageKey,
            'pageTitle' => $site['meta'][$pageKey]['title'],
            'pageDescription' => $site['meta'][$pageKey]['description'],
        ]);
    }

    private function localizedCatalog(string $locale): array
    {
        return $this->localize($this->store->all(), $locale);
    }

    private function localize(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (array_key_exists('en', $value) && array_key_exists('bn', $value) && count($value) === 2) {
            return $value[$locale] ?? $value['en'];
        }

        $localized = [];

        foreach ($value as $key => $item) {
            $localized[$key] = $this->localize($item, $locale);
        }

        return $localized;
    }
}
