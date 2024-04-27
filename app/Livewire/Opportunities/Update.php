<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.update');
    }

    #[Computed]
    public function statusOptions(): array
    {
        return [
            ['id' => 'open', 'name' => 'Open'],
            ['id' => 'won', 'name' => 'Won'],
            ['id' => 'lost', 'name' => 'Lost'],
        ];
    }

    #[On('opportunity::updating')]
    public function loadOpportunity(int $opportunityId): void
    {
        $opportunity = Opportunity::find($opportunityId);
        $this->form->setOpportunity($opportunity);

        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->update();

        $this->dispatch('opportunity::updated');
        $this->reset('modal');
        $this->success('Opportunity updated successfully.');
    }

    public function search(string $value = ''): void
    {
        $this->form->searchCustomers($value);
    }
}
