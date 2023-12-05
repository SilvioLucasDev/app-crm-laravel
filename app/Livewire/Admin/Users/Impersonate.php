<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;

class Impersonate extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div></div>
        HTML;
    }

    #[On('user::impersonation')]
    public function impersonate(int $userId): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);

        if(auth()->user()->id == $userId) {
            throw new Exception("You can not impersonate yourself");
        }

        session()->put('impersonator', auth()->user()->id);
        session()->put('impersonate', $userId);

        $this->redirectRoute('dashboard');
    }
}
