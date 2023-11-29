<?php

use App\Livewire\Admin;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/users', function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    actingAs($user);

    get(route('admin.users'))
        ->assertOk();
});

it('make sure that the route is protected by the permission BE_AN_ADMIN', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    get(route('admin.users'))
        ->assertForbidden();
});

it("let's create a livewire component to list as users in the page", function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory(9)->create();

    $livewire = Livewire::test(Admin\Users\Index::class);

    $livewire->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($users as $user) {
        $livewire->assertSee($user->name);
    }
});

it('check the table format', function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    actingAs($user);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ]);
});
