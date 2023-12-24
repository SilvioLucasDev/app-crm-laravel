<?php

namespace App\Livewire\Auth;

use App\Events\SendNewCode;
use Closure;
use Illuminate\Support\Facades\Event;
use Livewire\Component;

class EmailValidation extends Component
{
    public ?string $code = null;

    public function render()
    {
        return view('livewire.auth.email-validation');
    }

    public function handle(): void
    {
        $this->validate([
            'code' => function (string $attribute, mixed $value, Closure $fail) {
                if ($value !== auth()->user()->validation_code) {
                    $fail('Invalid code');
                }
            },
        ]);
    }

    public function sendNewCode(): void
    {
        $user = auth()->user();
        Event::dispatch(new SendNewCode($user));
    }
}
