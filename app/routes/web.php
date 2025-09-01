<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\PickController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/login', fn () => Socialite::driver('google')->redirect())->name('login');

Route::get('/auth/callback', function () {
    $identity = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate([
        'email' => $identity->getEmail(),
    ], [
        'name' => explode(' ', $identity->getName())[0],
        'password' => 'irrelevant_password',
    ]);

    Auth::login($user);

    return redirect('/');
});

Route::post('/logout', function () {
    Auth::logout();

    return redirect('/');
})->name('logout');

Route::get('/', [RoundController::class, 'index'])->name('rounds.index');

Route::get('/rounds/{round}', [RoundController::class, 'show'])->name('rounds.show');

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::controller(PickController::class)
    ->middleware('auth')
    ->prefix('/rounds/{round}/{type}/picks')
    ->group(function () {
        Route::get('/', 'create')->name('picks.create');
        Route::post('/', 'store')->name('picks.store');
    });

Route::controller(ResultController::class)
    ->middleware(['can:publish,App\\Models\\Result'])
    ->prefix('/results')
    ->group(function () {
        Route::get('/', 'create')->name('results.create');
        Route::post('/', 'store')->name('results.store');
    });

Route::controller(DriverController::class)
    ->middleware(['can:publish,App\\Models\\Result'])
    ->prefix('/drivers')
    ->group(function () {
        Route::get('/', 'index')->name('drivers.index');
        Route::post('/', 'update')->name('drivers.update');
    });

if (app()->environment('development')) {
    Route::get('/dev/login/{email}', function (string $email) {
        $user = User::where('email', $email)->firstOrFail();

        Auth::login($user);

        return redirect('/');
    });
}
