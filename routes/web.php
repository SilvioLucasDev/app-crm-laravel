<?php

use App\Enums\Can;
use App\Livewire\Auth\{Login, Password, Register};
use App\Livewire\{Admin, Welcome};
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
Route::get('/email-validation', fn () => 'oi')->middleware('auth')->name('auth.email-validation');
Route::get('/password/recovery', Password\Recovery::class)->name('auth.password.recovery');
Route::get('/password/reset', Password\Reset::class)->name('password.reset');

/**
 * Authenticated Routes
 */
Route::middleware('auth', 'email.verified')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    /**
     * Admin Routes
     */
    Route::prefix('/admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/users', Admin\Users\Index::class)->name('admin.users');
    });
});
