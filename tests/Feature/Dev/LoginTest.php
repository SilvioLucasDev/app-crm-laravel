<?php

use App\Livewire\Dev;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertAuthenticatedAs, get};

it('should be able to list all users of the system', function () {
    User::factory(10)->create();

    $users = User::all();

    Livewire::test(Dev\Login::class)
        ->assertSet('users', $users)
        ->assertSee($users->first()->name);
});

it('should be able to login with any user', function () {
    $user = User::factory()->create();

    Livewire::test(Dev\Login::class)
        ->set('selectedUser', $user->id)
        ->call('login')
        ->assertRedirect(route('dashboard'));

    assertAuthenticatedAs($user);
});

it('should not load the livewire component on production environment', function () {
    $user = User::factory()->create();
    actingAs($user);

    app()->detectEnvironment(fn () => 'production');

    get(route('dashboard'))
        ->assertDontSeeLivewire('dev.login');
});

it('should load the livewire component on non production environment', function () {
    $user = User::factory()->create();
    actingAs($user);

    app()->detectEnvironment(fn () => 'local');

    get(route('dashboard'))
        ->assertSeeLivewire('dev.login');
});
