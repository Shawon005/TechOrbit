<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('site.home', ['locale' => 'en']));

Route::prefix('{locale}')
    ->where(['locale' => 'en|bn'])
    ->group(function (): void {
        Route::get('/', [SiteController::class, 'home'])->name('site.home');
        Route::get('/software', [SiteController::class, 'software'])->name('site.software');
        Route::get('/services', [SiteController::class, 'services'])->name('site.services');
        Route::get('/portfolio', [SiteController::class, 'portfolio'])->name('site.portfolio');
        Route::get('/about', [SiteController::class, 'about'])->name('site.about');
        Route::get('/blog', [SiteController::class, 'blog'])->name('site.blog');
        Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
        Route::post('/contact', [SiteController::class, 'contactStore'])->name('site.contact.store');
    });

Route::prefix('admin')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
    });

    Route::middleware('auth')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');
    });

    Route::get('/', fn () => Auth::check() ? redirect()->route('admin.dashboard') : redirect()->route('login'));

    Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/hero-slides', [AdminController::class, 'heroSlides'])->name('admin.hero-slides');
    Route::get('/software', [AdminController::class, 'software'])->name('admin.software');
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::get('/portfolio', [AdminController::class, 'portfolio'])->name('admin.portfolio');
    Route::get('/team', [AdminController::class, 'team'])->name('admin.team');
    Route::get('/blog', [AdminController::class, 'blog'])->name('admin.blog');
    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/content/{section}', [AdminController::class, 'storeCollection'])->name('admin.content.store');
    Route::put('/content/{section}/{id}', [AdminController::class, 'updateCollection'])->name('admin.content.update');
    Route::delete('/content/{section}/{id}', [AdminController::class, 'destroyCollection'])->name('admin.content.destroy');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    });
});
