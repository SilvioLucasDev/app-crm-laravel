<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public ?String $email = null;

    public ?String $password = null;

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function tryToLogin(): void
    {
        $this->ensureIsNotRateLimiting();

        if(!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());
            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }
        $this->redirectRoute('dashboard');
    }

    private function ensureIsNotRateLimiting()
    {
        if(RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $this->addError('rateLimiter', trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]));

            return true;
        }

        return false;
    }

    private function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
