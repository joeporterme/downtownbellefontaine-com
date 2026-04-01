<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Business\DashboardController;
use App\Http\Controllers\Events\EventController;
use App\Http\Controllers\Public\BusinessController as PublicBusinessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Public Pages
Route::get('/things-to-do', [App\Http\Controllers\Public\PageController::class, 'thingsToDo'])->name('pages.things-to-do');
Route::get('/places-to-shop', [App\Http\Controllers\Public\PageController::class, 'placesToShop'])->name('pages.places-to-shop');
Route::get('/food-drinks', [App\Http\Controllers\Public\PageController::class, 'foodDrinks'])->name('pages.food-drinks');
Route::get('/first-fridays', fn() => view('pages.first-fridays'))->name('pages.first-fridays');
Route::get('/meeting-spaces', fn() => view('pages.meeting-spaces'))->name('pages.meeting-spaces');
Route::get('/dora', fn() => view('pages.dora'))->name('pages.dora');
Route::get('/media', fn() => view('pages.media'))->name('pages.media');
Route::get('/contact', fn() => view('pages.contact'))->name('pages.contact');
Route::get('/historic-walking-tour', fn() => view('pages.historic-walking-tour'))->name('pages.historic-walking-tour');
Route::get('/privacy-policy', fn() => view('pages.privacy-policy'))->name('pages.privacy-policy');

// Public Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Public Business Directory
Route::get('/businesses', [PublicBusinessController::class, 'index'])->name('businesses.index');
Route::get('/businesses/category/{category:slug}', [PublicBusinessController::class, 'category'])->name('businesses.category');
Route::get('/businesses/{business:slug}', [PublicBusinessController::class, 'show'])->name('businesses.show');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Business Owner Portal
Route::prefix('portal')->middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('business.dashboard');
    Route::get('/business/create', [BusinessController::class, 'create'])->name('business.create');
    Route::post('/business', [BusinessController::class, 'store'])->name('business.store');
    Route::get('/business/{business}/edit', [BusinessController::class, 'edit'])->name('business.edit');
    Route::put('/business/{business}', [BusinessController::class, 'update'])->name('business.update');

    // Events Management
    Route::get('/events', [EventController::class, 'myEvents'])->name('business.events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});
