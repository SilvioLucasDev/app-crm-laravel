<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Notifications\Auth\ValidationCodeNotification;
use Illuminate\Auth\Events\Registered;

class CreateValidationCode
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->validation_code = random_int(100000, 999999);
        $user->save();

        $user->notify(new ValidationCodeNotification());
    }
}
