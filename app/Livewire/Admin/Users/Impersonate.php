<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
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

        session()->put('impersonator', auth()->user()->id);
        session()->put('impersonate', $userId);

        $this->redirect(route('dashboard'));
    }
}
