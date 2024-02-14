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

    $livewire->assertSet('items', function ($items) {
        expect($items)
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
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'permissions', 'label' => 'Permissions', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by name and email', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->create(['name' => 'Is Admin', 'email' => 'is_dmin@mail.com']);
    actingAs($admin);
    User::factory()->create(['name' => 'Is User', 'email' => 'any_email@mail.com']);

    Livewire::test(Admin\Users\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'user')
        ->assertSet('items', function ($items) {
            expect($items)
                ->toHaveCount(1)
                ->first()->name->toBe('Is User');

            return true;
        })
        ->set('search', 'any')
        ->assertSet('items', function ($items) {
            expect($items)
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
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('searchPermissions', [$permission->id])
        ->assertSet('items', function ($items) {
            expect($items)
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
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(1);

            return true;
        })
        ->set('searchTrash', true)
        ->assertSet('items', function ($items) {
            expect($items) ->toHaveCount(2);

            return true;
        });
});

it('should be able to sort by id, name and email', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->create(['name' => 'Is Admin', 'email' => 'is_dmin@mail.com']);
    User::factory()->create(['name' => 'Is User', 'email' => 'any_email@mail.com']);
    actingAs($admin);

    // ASC => Is Admin, Is User
    // DESC => Is User, Is Admin
    Livewire::test(Admin\Users\Index::class)
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('Is Admin')
                ->and($items)->last()->name->toBe('Is User');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'name')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->name->toBe('Is User')
                ->and($items)->last()->name->toBe('Is Admin');

            return true;
        });
});

it("should be able to paginate the result", function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    User::factory(30)->create();
    actingAs($user);

    Livewire::test(Admin\Users\Index::class)
    ->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    })
    ->set('perPage', 15)
    ->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(15);

        return true;
    });
});
