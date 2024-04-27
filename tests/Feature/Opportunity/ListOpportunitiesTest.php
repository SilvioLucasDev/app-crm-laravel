<?php

use App\Livewire\Opportunities;
use App\Models\{Opportunity, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route opportunities', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    get(route('opportunities'))
        ->assertOk();
});

it("let's create a livewire component to list as opportunities in the page", function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $opportunities = Opportunity::factory(10)->create();

    $livewire = Livewire::test(Opportunities\Index::class);

    $livewire->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($opportunities as $opportunity) {
        $livewire->assertSee($opportunity->title);
    }
});

it('check the table format', function () {
    /** @var User $user */
    $user = User::factory()->admin()->create();
    actingAs($user);

    Livewire::test(Opportunities\Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'title', 'label' => 'Title', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'status', 'label' => 'Status', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
            ['key' => 'amount', 'label' => 'Amount', 'sortColumnBy' => 'id', 'sortDirection' => 'asc'],
        ]);
});

it('should be able to filter by title', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Opportunity::factory()->create(['title' => 'PHP']);
    Opportunity::factory()->create(['title' => 'Node']);

    Livewire::test(Opportunities\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'PHP')
        ->assertSet('items', function ($items) {
            expect($items)
                ->toHaveCount(1)
                ->first()->title->toBe('PHP');

            return true;
        });
});

it('should be able to sort by id, title and status', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Opportunity::factory()->create(['title' => 'PHP']);
    Opportunity::factory()->create(['title' => 'Node']);

    // ASC => Node, PHP
    // DESC => PHP, Node
    Livewire::test(Opportunities\Index::class)
        ->set('sortDirection', 'asc')
        ->set('sortColumnBy', 'title')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('Node')
                ->and($items)->last()->title->toBe('PHP');

            return true;
        })
        ->set('sortDirection', 'desc')
        ->set('sortColumnBy', 'title')
        ->assertSet('items', function ($items) {
            expect($items)
                ->first()->title->toBe('PHP')
                ->and($items)->last()->title->toBe('Node');

            return true;
        });
});

it("should be able to paginate the result", function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    Opportunity::factory(30)->create();

    Livewire::test(Opportunities\Index::class)
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(10);

            return true;
        })
        ->set('perPage', 15)
        ->assertSet('items', function ($items) {
            expect($items)
                ->toBeInstanceOf(LengthAwarePaginator::class)
                ->toHaveCount(15);

            return true;
        });
});

it('should be able to list archive opportunities', function () {
    Opportunity::factory()->create();
    Opportunity::factory(2)->deleted()->create();

    Livewire::test(Opportunities\Index::class)
    ->assertSet('items', function ($items) {
        expect($items)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(1);

        return true;
    })
    ->set('searchTrash', true)
    ->assertSet('items', function ($items) {
        expect($items)->toHaveCount(2);

        return true;
    });
});
