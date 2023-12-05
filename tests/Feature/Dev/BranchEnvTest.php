<?php

use App\Livewire\Dev;
use App\Models\User;
use Illuminate\Support\Facades\Process;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should show a current branch in the page', function () {
    Process::fake([
        'git branch --show-current' => Process::result('SLDS'),
    ]);

    Livewire::test(Dev\BranchEnv::class)
        ->assertSet('branch', 'SLDS')
        ->assertSee('SLDS');

    Process::assertRan('git branch --show-current');
});

it('should not load the livewire component on production environment', function () {
    $user = User::factory()->create();
    actingAs($user);

    app()->detectEnvironment(fn () => 'production');

    get(route('dashboard'))
        ->assertDontSeeLivewire('dev.branch-env');
});

it('should load the livewire component on non production environment', function () {
    $user = User::factory()->create();
    actingAs($user);

    app()->detectEnvironment(fn () => 'local');

    get(route('dashboard'))
        ->assertSeeLivewire('dev.branch-env');
});
