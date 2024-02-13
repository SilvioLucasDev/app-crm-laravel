<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route customers', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    get(route('customers'))
        ->assertOk();
});

it("let's create a livewire component to list as customers in the page", function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $customers = Customer::factory(10)->create();

    $livewire = Livewire::test(Customers\Index::class);

    $livewire->assertSet('customers', function ($customers) {
        expect($customers)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($customers as $customer) {
        $livewire->assertSee($customer->name);
    }
});

it('check the table format', function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    actingAs($user);

    Livewire::test(Customers\Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by name and email', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Customer::factory()->create(['name' => 'Joe', 'email' => 'doe@mail.com']);
    Customer::factory()->create(['name' => 'Any', 'email' => 'user@mail.com']);

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'joe')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toHaveCount(1)
                ->first()->name->toBe('Joe');

            return true;
        })
        ->set('search', 'User')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toHaveCount(1)
                ->first()->name->toBe('Any');

            return true;
        });
});

it('should be able to sort by id, name and email', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Customer::factory()->create(['name' => 'Joe', 'email' => 'doe@mail.com']);
    Customer::factory()->create(['name' => 'Any', 'email' => 'user@mail.com']);

    // ASC => Any, Joe
    // DESC => Joe, Any
    Livewire::test(Customers\Index::class)
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'name')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('Any')
                ->and($customers)->last()->name->toBe('Joe');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'name')
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->first()->name->toBe('Joe')
                ->and($customers)->last()->name->toBe('Any');

            return true;
        });
});

it("should be able to paginate the result", function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Customer::factory(30)->create();

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(10);

            return true;
        })
        ->set('perPage', 15)
        ->assertSet('customers', function ($customers) {
            expect($customers)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(15);

            return true;
        });
});
