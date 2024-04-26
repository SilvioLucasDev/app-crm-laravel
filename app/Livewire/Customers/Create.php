<?php

namespace App\Livewire\Customers;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.create');
    }

    #[On('customer::creating')]
    public function openModal(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->create();

        $this->dispatch('customer::created');
        $this->reset('modal');
        $this->success('Customer created successfully.');
    }
}
