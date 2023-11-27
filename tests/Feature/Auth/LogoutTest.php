<?php

use App\Livewire\Auth\Logout;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('should be able to logout of the application', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect(route('auth.logout'));

    expect(auth())->guest()->toBeTrue();
});
