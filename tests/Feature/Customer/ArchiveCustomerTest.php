<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

it('should be able to archive a customer', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $customer = Customer::factory()->create();

    Livewire::test(Customers\Archive::class)
        ->set('customer', $customer)
        ->call('archive');

    assertSoftDeleted('customers', [
        'id' => $customer->id,
    ]);

    $customer->refresh();

    expect($customer)->deleted_at->not->toBeNull();
});
