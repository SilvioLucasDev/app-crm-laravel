<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Opportunity $opportunity = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.archive');
    }

    #[On('opportunity::archiving')]
    public function openConfirmationFor(int $opportunityId): void
    {
        $this->opportunity = Opportunity::select('id', 'title')->find($opportunityId);
        $this->modal       = true;
    }

    public function archive(): void
    {
        $this->opportunity->delete();

        $this->dispatch('opportunity::archived');
        $this->reset('modal');
        $this->success('Opportunity archived successfully.');
    }
}
