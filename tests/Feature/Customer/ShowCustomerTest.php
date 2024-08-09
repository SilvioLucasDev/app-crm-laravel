<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

beforeEach(function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);
});

it('should be able to access the route customers show', function () {
    $customer = Customer::factory()->create();

    get(route('customers.show', $customer))
        ->assertOk();
});

it("should show all the customer information in the page", function () {
    $customer = Customer::factory()->create();

    $livewire = Livewire::test(Customers\Show::class, ['customer' => $customer]);

    $livewire->assertSee($customer->name)
        ->assertSee($customer->email)
        ->assertSee($customer->phone)
        ->assertSee($customer->linkedin)
        ->assertSee($customer->facebook)
        ->assertSee($customer->twitter)
        ->assertSee($customer->instagram)
        ->assertSee($customer->address)
        ->assertSee($customer->city)
        ->assertSee($customer->state)
        ->assertSee($customer->country)
        ->assertSee($customer->zip)
        ->assertSee($customer->age)
        ->assertSee($customer->gender)
        ->assertSee($customer->company)
        ->assertSee($customer->position)
        ->assertSee($customer->created_at->diffForHumans())
        ->assertSee($customer->updated_at->diffForHumans());
});
