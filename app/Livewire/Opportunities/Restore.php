<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Opportunity $opportunity = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.restore');
    }

    #[On('opportunity::restoring')]
    public function openConfirmationFor(int $opportunityId): void
    {
        $this->opportunity = Opportunity::select('id', 'title')->onlyTrashed()->find($opportunityId);
        $this->modal       = true;
    }

    public function restore(): void
    {
        $this->opportunity->restore();

        $this->dispatch('opportunity::restored');
        $this->reset('modal');
        $this->success('opportunity restored successfully.');
    }
}
