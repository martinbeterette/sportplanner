<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Google login
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
        'avatar' => $user_google->avatar,
        'email_verified_at' => now(),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

// Facebook login
Route::get('/facebook-auth/redirect', function () {
    return Socialite::driver('facebook')->redirect();
});
 
Route::get('/facebook-auth/callback', function () {
    $user_facebook = Socialite::driver('facebook')->stateless()->user();

    $user = User::updateOrCreate([
        'facebook_id' => $user_facebook->id,
    ], [
        'name' => $user_facebook->name,
        'email' => $user_facebook->email,
        'avatar' => $user_facebook->avatar,
        'email_verified_at' => now(),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
