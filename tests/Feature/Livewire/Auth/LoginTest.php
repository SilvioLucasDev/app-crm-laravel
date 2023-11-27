<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'any@email.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'any@email.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should make sure to inform the user of an error when the email and password do not work', function () {
    Livewire::test(Login::class)
        ->set('email', 'any@email.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));
});