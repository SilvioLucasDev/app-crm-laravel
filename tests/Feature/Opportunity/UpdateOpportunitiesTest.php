<?php

use App\Livewire\Opportunities;
use App\Models\{Opportunity, User};
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $this->opportunity = Opportunity::factory()->create();
});

it('renders successfully', function () {
    Livewire::test(Opportunities\Update::class)
        ->assertStatus(200);
});

it('should be able to update a opportunity', function () {
    Livewire::test(Opportunities\Update::class)
        ->call('loadOpportunity', $this->opportunity->id)
        ->set('form.title', 'PHP')
        ->assertPropertyWired('form.title')
        ->set('form.status', 'won')
        ->assertPropertyWired('form.status')
        ->set('form.amount', '10.00')
        ->assertPropertyWired('form.amount')
        ->call('save')
        ->assertMethodWiredToForm('save')
        ->assertHasNoErrors();

    assertDatabaseHas('opportunities', [
        'id'     => $this->opportunity->id,
        'title'  => 'PHP',
        'status' => 'won',
        'amount' => '1000',
    ]);

    assertDatabaseCount('opportunities', 1);
});

it('validation rules', function ($f) {
    $livewire = Livewire::test(Opportunities\Update::class)
        ->call('loadOpportunity', $this->opportunity->id)
        ->set('form.' . $f->field, $f->value);

    $livewire->call('save')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'title::required'  => (object)['field' => 'title', 'value' => '', 'rule' => 'required'],
    'title::min:3'     => (object)['field' => 'title', 'value' => str_repeat('*', 2), 'rule' => 'min'],
    'title::max:100'   => (object)['field' => 'title', 'value' => str_repeat('*', 101), 'rule' => 'max'],
    'status::required' => (object)['field' => 'status', 'value' => '', 'rule' => 'required'],
    'status::in'       => (object)['field' => 'status', 'value' => 'wrong', 'rule' => 'in'],
    'amount::required' => (object)['field' => 'amount', 'value' => '', 'rule' => 'required'],
]);

test('after updated we should dispatch an event to tell the list to reload', function () {
    Livewire::test(Opportunities\Update::class)
        ->call('loadOpportunity', $this->opportunity->id)
        ->set('form.title', 'PHP')
        ->set('form.status', 'won')
        ->set('form.amount', '10.00')
        ->call('save')
        ->assertDispatched('opportunity::updated');
});

test('after updated we should close the modal', function () {
    Livewire::test(Opportunities\Update::class)
        ->call('loadOpportunity', $this->opportunity->id)
        ->set('form.title', 'PHP')
        ->set('form.status', 'won')
        ->set('form.amount', '10.00')
        ->call('save')
        ->assertSet('modal', false);
});

test('check if component is in the page', function () {
    Livewire::test(Opportunities\Index::class)
        ->assertContainsLivewireComponent('opportunities.update');
});
