<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $this->customer = Customer::factory()->create();
});

it('renders successfully', function () {
    Livewire::test(Customers\Update::class)
        ->assertStatus(200);
});

it('should be able to update a customer', function () {
    Livewire::test(Customers\Update::class)
        ->set('customer', $this->customer)
        ->set('customer.name', 'Any User')
        ->assertPropertyWired('customer.name')
        ->set('customer.email', 'any@email.com')
        ->assertPropertyWired('customer.email')
        ->set('customer.phone', '123456789')
        ->assertPropertyWired('customer.phone')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'id'    => $this->customer->id,
        'type'  => 'customer',
        'name'  => 'Any User',
        'email' => 'any@email.com',
        'phone' => '123456789',
    ]);

    assertDatabaseCount('customers', 1);
});
