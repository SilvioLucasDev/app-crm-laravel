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
