<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public function mount(): void
    {
        $this->token = request('token');

        if(!$this->tokenIsValid()) {
            session()->flash('status', 'Token invalid');
            $this->redirectRoute('auth.login');
        }
    }

    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenIsValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get('token');

        foreach ($tokens as $token) {
            if(Hash::check($this->token, $token->token)) {
                return true;
            }
        }

        return false;
    }
}
