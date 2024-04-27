<?php

use App\Livewire\Opportunities;
use App\Models\Opportunity;
use Livewire\Livewire;

use function Pest\Laravel\assertSoftDeleted;

it('should be able to archive a opportunity', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Opportunities\Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive');

    assertSoftDeleted('opportunities', [
        'id' => $opportunity->id,
    ]);

    $opportunity->refresh();

    expect($opportunity)->deleted_at->not->toBeNull();
});

test('when confirming we should load the opportunity and set modal to true', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Opportunities\Archive::class)
        ->call('openConfirmationFor', $opportunity->id)
        ->assertSet('opportunity.id', $opportunity->id)
        ->assertSet('modal', true);
});

test('after archiving we should dispatch an event to tell the list to reload', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Opportunities\Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive')
        ->assertDispatched('opportunity::archived');
});

test('after archiving we should close the modal', function () {
    $opportunity = Opportunity::factory()->create();

    Livewire::test(Opportunities\Archive::class)
        ->set('opportunity', $opportunity)
        ->call('archive')
        ->assertSet('modal', false);
});

test('making sure archive method is wired', function () {
    Livewire::test(Opportunities\Archive::class)
        ->assertMethodWired('archive()');
});

test('check if component is in the page', function () {
    Livewire::test(Opportunities\Index::class)
        ->assertContainsLivewireComponent('opportunities.archive');
});
