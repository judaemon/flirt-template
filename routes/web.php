<?php

use App\Http\Controllers\NmsOAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Nms\Oauth\Facades\NmsOauthFacade as NmsOauth;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('oauth')->group(function () {
    Route::get('/nms', function () {
        if (auth()->check()) {
            return redirect('/admin');
        }
        return redirect('login');
    })->name('nms');

    Route::post('auth/logout', function () {
        \Auth::logout();
        \Session::flush();
        \Cache::flush();
        NmsOauth::logout();

        return redirect('/');
    })->name('auth.logout');
    })->where('path', '^(?!oauth\/).*');

    Route::get('/oauth/callback', [NmsOAuthController::class, 'callback'])->name('oauth.callback');

require __DIR__.'/settings.php';
