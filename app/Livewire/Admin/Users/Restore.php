<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\UserRestoredNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?User $user = null;

    #[Rule(['required', 'confirmed'])]
    public string $confirmation = 'YODA';

    public ?string $confirmation_confirmation = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.admin.users.restore');
    }

    #[On('user::restoring')]
    public function openConfirmationFor(int $userId): void
    {
        $this->user  = User::select('id', 'name')->onlyTrashed()->find($userId);
        $this->modal = true;
    }

    public function restore(): void
    {
        $this->validate();

        $this->user->restore();
        $this->user->notify(new UserRestoredNotification());
        $this->dispatch('user::restored');
        $this->reset('modal');
        $this->success('User restored successfully.');
    }
}
