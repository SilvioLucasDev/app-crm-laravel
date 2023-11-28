<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash, Password};
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    #[Rule(['required', 'email', 'confirmed'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if(!$this->tokenIsValid()) {
            session()->flash('status', trans('auth.token'));
            $this->redirectRoute('auth.login');
        }
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    public function updatePassword(): void
    {
        $this->validate();
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password       = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );
        session()->flash('status', trans($status));
        $this->redirectRoute('dashboard');
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
