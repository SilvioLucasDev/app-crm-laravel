<?php

use App\Livewire\Admin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

it('should add a key impersonate to the session with the given user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();
    actingAs($admin);

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));
    assertTrue(session()->has('impersonator'));

    assertSame(session()->get('impersonate'), $user->id);
    assertSame(session()->get('impersonator'), $admin->id);
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

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id);

    Livewire::test(Admin\Users\StopImpersonate::class)
    ->call('stop')
    ->assertRedirect(route('admin.users'));

    expect(session('impersonate'))->toBeNull();

    get(route('dashboard'))
        ->assertDontSee(trans("You're impersonating :name, click here to stop the impersonation.", ['name' => $user->name]));

    expect(auth()->id())->toBe($admin->id);
});

it('should have the correct permission to impersonate someone', function () {
    $admin    = User::factory()->admin()->create();
    $nonAdmin = User::factory()->create();
    $user     = User::factory()->create();
    actingAs($nonAdmin);

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertForbidden();

    actingAs($admin);
    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect();
});

it('should not be possible to impersonate myself', function () {
    $admin = User::factory()->admin()->create();
    actingAs($admin);

    Livewire::test(Admin\Users\Impersonate::class)
        ->call('impersonate', $admin->id);
})->throws(Exception::class);

test('check if component is in the page', function () {
    actingAs(User::factory()->admin()->create());
    Livewire::test(Admin\Users\Index::class)
        ->assertContainsLivewireComponent('admin.users.impersonate');
});
