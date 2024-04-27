<?php

namespace App\Livewire\Opportunities;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.create');
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

    #[On('opportunity::creating')]
    public function openModal(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->create();

        $this->dispatch('opportunity::created');
        $this->reset('modal');
        $this->success('Opportunity created successfully.');
    }
}
