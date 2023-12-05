<?php

use App\Livewire\Dev;
use App\Models\User;
use Livewire\Livewire;

it('should be able to list all users of the system', function () {
    User::factory(10)->create();

    $users = User::all();

    Livewire::test(Dev\Login::class)
        ->assertSet('users', $users)
        ->assertSee($users->first()->name);
});
