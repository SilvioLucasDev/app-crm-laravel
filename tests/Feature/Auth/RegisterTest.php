<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Event, Notification};
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'Any User')
        ->set('email', 'any@email.com')
        ->set('email_confirmation', 'any@email.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'Any User',
        'email' => 'any@email.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())
        ->id->toBe(User::first()->id);
});

it('validation rules', function ($f) {
    if($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if(property_exists($f, 'additionalField')) {
        $livewire->set($f->additionalField, $f->additionalValue);
    }

    $livewire->call('submit')
    ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required'     => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max:255'      => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required'    => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'       => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255'     => (object)['field' => 'email', 'value' => str_repeat('*', 256) . '@email.com', 'rule' => 'max'],
    'email::confirmed'   => (object)['field' => 'email', 'value' => 'any@email.com', 'rule' => 'confirmed'],
    'email::unique'      => (object)['field' => 'email', 'value' => 'any@email.com', 'rule' => 'unique', 'additionalField' => 'email_confirmation', 'additionalValue' => 'any@email.com'],
    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
]);

it('should dispatch Registered event', function () {
    Event::fake();

    Livewire::test(Register::class)
        ->set('name', 'Any User')
        ->set('email', 'any@email.com')
        ->set('email_confirmation', 'any@email.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    Event::assertDispatched(Registered::class);
});
