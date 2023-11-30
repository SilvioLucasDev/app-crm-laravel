<?php

use App\Enums\Can;
use App\Livewire\Admin;
use App\Models\{Permission, User};
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

it('should be able to filter by name and email', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->create(['name' => 'Is Admin', 'email' => 'is_dmin@mail.com']);
    actingAs($admin);
    User::factory()->create(['name' => 'Is User', 'email' => 'any_email@mail.com']);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'user')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Is User');

            return true;
        })
        ->set('search', 'any')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Is User');

            return true;
        });
});

it('should be able to filter by permission.key', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->create(['name' => 'Is Admin', 'email' => 'is_dmin@mail.com']);
    actingAs($admin);
    User::factory()->create(['name' => 'Is User', 'email' => 'any_email@mail.com']);
    $permission = Permission::where('key', '=', Can::BE_AN_ADMIN)->first();

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('search_permissions', [$permission->id])
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Is Admin');

            return true;
        });
});

it('should be able to list deleted users', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->create(['name' => 'Is Admin', 'email' => 'is_dmin@mail.com']);
    User::factory(2)->create(['deleted_at' => now()]);
    actingAs($admin);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('users', function ($users) {
            expect($users) ->toHaveCount(2);

            return true;
        });
});
