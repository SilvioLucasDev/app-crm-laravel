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
        ->call('loadCustomer', $this->customer->id)
        ->set('form.name', 'Any User')
        ->assertPropertyWired('form.name')
        ->set('form.email', 'any@email.com')
        ->assertPropertyWired('form.email')
        ->set('form.phone', '123456789')
        ->assertPropertyWired('form.phone')
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
    $livewire = Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.' . $f->field, $f->value);

    $livewire->call('save')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required' => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::min:3'    => (object)['field' => 'name', 'value' => str_repeat('*', 2), 'rule' => 'min'],
    'name::max:255'  => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::email'   => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255' => (object)['field' => 'email', 'value' => str_repeat('*', 256) . '@email.com', 'rule' => 'max'],
]);

test('validates email is required without phone', function () {
    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.phone', '')
        ->set('form.email', '')
        ->call('save')
        ->assertHasErrors(['email' => 'required_without']);
});

test('validates email is unique', function () {
    Customer::factory()->create(['email' => 'any@email.com']);

    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.email', 'any@email.com')
        ->call('save')
        ->assertHasErrors(['email' => 'unique']);

    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.email', $this->customer->email)
        ->call('save')
        ->assertHasNoErrors(['email' => 'unique']);
});

test('validates phone is required without email', function () {
    $livewire = Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.email', '')
        ->set('form.phone', '')
        ->call('save')
        ->assertHasErrors(['phone' => 'required_without']);
});

test('validates phone is unique', function () {
    Customer::factory()->create(['phone' => '11988883333']);

    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.phone', '11988883333')
        ->call('save')
        ->assertHasErrors(['phone' => 'unique']);

    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.phone', $this->customer->phone)
        ->call('save')
        ->assertHasNoErrors(['phone' => 'unique']);
});

test('after updated we should dispatch an event to tell the list to reload', function () {
    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.name', 'Any User')
        ->set('form.email', 'any@email.com')
        ->set('form.phone', '123456789')
        ->call('save')
        ->assertDispatched('customer::updated');
});

test('after updated we should close the modal', function () {
    Livewire::test(Customers\Update::class)
        ->call('loadCustomer', $this->customer->id)
        ->set('form.name', 'Any User')
        ->set('form.email', 'any@email.com')
        ->set('form.phone', '123456789')
        ->call('save')
        ->assertSet('modal', false);
});

test('check if component is in the page', function () {
    Livewire::test(Customers\Index::class)
        ->assertContainsLivewireComponent('customers.update');
});
