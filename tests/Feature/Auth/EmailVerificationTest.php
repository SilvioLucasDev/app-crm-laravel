<?php

use App\Listeners\Auth\CreateValidationCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    Notification::fake();
});

it('should create a new validation code and sabe in the users table', function () {
    $user = User::factory()->create();

    $event    = new Registered($user);
    $listener = new CreateValidationCode();
    $listener->handle($event);

    $user->refresh();

    expect($user)->validation_code->not->toBeNull()
        ->and($user)->validation_code->toBeNumeric();

    assertTrue(str($user->validation_code)->length() == 6);
});

it('should send that new code to the user via email', function () {
})->todo();

it('making sure that the listener to send the code is linked to the Registered event', function () {
})->todo();
