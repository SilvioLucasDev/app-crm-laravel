<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);
});

it('renders successfully', function () {
    Livewire::test(Customers\Create::class)
        ->assertStatus(200);
});

it('should be able to create a new customer in the system', function () {
    Livewire::test(Customers\Create::class)
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
        'type'  => 'customer',
        'name'  => 'Any User',
        'email' => 'any@email.com',
        'phone' => '123456789',
    ]);

    assertDatabaseCount('customers', 1);
});

it('validation rules', function ($f) {
    if($f->rule == 'unique') {
        Customer::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Customers\Create::class)
    ->set('form.' . $f->field, $f->value);

    $livewire->call('save')
        ->assertHasErrors([$f->field => $f->rule]);
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

test('after created we should dispatch an event to tell the list to reload', function () {
    Livewire::test(Customers\Create::class)
        ->set('form.name', 'Any User')
        ->set('form.email', 'any@email.com')
        ->set('form.phone', '123456789')
        ->call('save')
        ->assertDispatched('customer::created');
});

test('after created we should close the modal', function () {
    Livewire::test(Customers\Create::class)
        ->set('form.name', 'Any User')
        ->set('form.email', 'any@email.com')
        ->set('form.phone', '123456789')
        ->call('save')
        ->assertSet('modal', false);
});

test('check if component is in the page', function () {
    Livewire::test(Customers\Index::class)
        ->assertContainsLivewireComponent('customers.create');
});
