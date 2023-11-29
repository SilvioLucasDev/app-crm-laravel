<?php

use App\Livewire\Auth\{Login, Password, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Auth Flow Routes
 */
Route::get('/login', Login::class)->name('auth.login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/logout', fn () => Auth::logout())->name('auth.logout');
Route::get('/password/recovery', Password\Recovery::class)->name('auth.password.recovery');
Route::get('/password/reset', Password\Reset::class)->name('password.reset');

/**
 * Authenticated Routes
 */
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    /**
     * Admin Routes
     */
    Route::prefix('/admin')->middleware('can:be-an-admin')->group(function () {
        Route::get('/dashboard', fn () => 'Admin Dashboard')->name('admin.dashboard');
    });
});
