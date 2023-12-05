<?php

use App\Livewire\Admin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $user = User::factory()->create();

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));

    assertSame(session()->get('impersonate'), $user->id);
});

it('should make sure that we are logged with the impersonate user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('dashboard'));

    get(route('dashboard'))
        ->assertSee(trans("You're impersonating :name, click here to stop the impersonation.", ['name' => $user->name]));

    expect(auth()->id())->toBe($user->id);
});

it('should be able to stop impersonation', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect(route('dashboard'));

    Livewire::test(Admin\Users\StopImpersonate::class)
    ->call('stop')
    ->assertRedirect(route('admin.users'));

    expect(session('impersonate'))->toBeNull();

    get(route('dashboard'))
        ->assertDontSee(trans("You're impersonating :name, click here to stop the impersonation.", ['name' => $user->name]));

    expect(auth()->id())->toBe($admin->id);
});