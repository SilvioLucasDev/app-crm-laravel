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

it('validation rules', function ($f) {
    if ($f->rule == 'unique') {
        Customer::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Customers\Update::class)
        ->set('customer', $this->customer)
        ->set('customer.' . $f->field, $f->value);

    $livewire->call('save')
        ->assertHasErrors(['customer.' . $f->field => $f->rule]);
})->with([
    'name::required'                => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::min:3'                   => (object)['field' => 'name', 'value' => str_repeat('*', 2), 'rule' => 'min'],
    'name::max:255'                 => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required_without:phone' => (object)['field' => 'email', 'value' => '', 'rule' => 'required_without'],
    'email::email'                  => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255'                => (object)['field' => 'email', 'value' => str_repeat('*', 256) . '@email.com', 'rule' => 'max'],
    'email::unique'                 => (object)['field' => 'email', 'value' => 'any@email.com', 'rule' => 'unique'],
    'phone::required_without:email' => (object)['field' => 'phone', 'value' => '', 'rule' => 'required_without'],
    'phone::unique'                 => (object)['field' => 'phone', 'value' => '123456789', 'rule' => 'unique'],
]);

test('after updated we should dispatch an event to tell the list to reload', function () {
    Livewire::test(Customers\Update::class)
        ->set('customer', $this->customer)
        ->set('customer.name', 'Any User')
        ->set('customer.email', 'any@email.com')
        ->set('customer.phone', '123456789')
        ->call('save')
        ->assertDispatched('customer::updated');
});

test('after updated we should close the modal', function () {
    Livewire::test(Customers\Update::class)
        ->set('customer', $this->customer)
        ->set('customer.name', 'Any User')
        ->set('customer.email', 'any@email.com')
        ->set('customer.phone', '123456789')
        ->call('save')
        ->assertSet('modal', false);
});

test('check if component is in the page', function () {
    Livewire::test(Customers\Index::class)
        ->assertContainsLivewireComponent('customers.update');
});
