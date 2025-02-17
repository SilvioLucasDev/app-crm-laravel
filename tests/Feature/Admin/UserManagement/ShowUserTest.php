<?php

use App\Livewire\Admin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('should be able to show all the details of the user in the component', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->deleted()->create();
    actingAs($admin);

    Livewire::test(Admin\Users\Show::class)
        ->call('loadUser', $user->id)
        ->assertSet('user.id', $user->id)
        ->assertSet('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertSee($user->updated_at->format('d/m/Y H:i'))
        ->assertSee($user->deleted_at->format('d/m/Y H:i'))
        ->assertSee($user->deletedBy->name);
});

it('should open the modal when the event is dispatched', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();
    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->call('show', $user->id)
        ->assertDispatched('user::showing', userId: $user->id);
});

it('making sure that the the method loadUser has the attribute On', function () {

    $livewireClass = new Admin\Users\Show();

    $reflection = new ReflectionClass($livewireClass);

    $attributes = $reflection->getMethod('loadUser')->getAttributes();

    expect($attributes)->toHaveCount(1);

    /** @var ReflectionAttribute $attribute */
    $attribute = $attributes[0];

    expect($attribute)->getName()->toBe('Livewire\Attributes\On')
        ->and($attribute)->getArguments()->toHaveCount(1);

    $argument = $attribute->getArguments()[0];
    expect($argument)->toBe('user::showing');
});

test('check if component is in the page', function () {
    actingAs(User::factory()->admin()->create());
    Livewire::test(Admin\Users\Index::class)
        ->assertContainsLivewireComponent('admin.users.show');
});
