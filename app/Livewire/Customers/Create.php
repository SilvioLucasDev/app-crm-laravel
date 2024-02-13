<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;

class Create extends Component
{
    public bool $modal = false;

    #[Rule(['required', 'min:3', 'max:255'])]
    public string $name = '';

    #[Rule(['required_without:phone', 'email', 'max:255', 'unique:customers'])]
    public string $email = '';

    #[Rule(['required_without:email', 'unique:customers'])]
    public string $phone = '';

    public function render(): View
    {
        return view('livewire.customers.create');
    }

    #[On('customer::create')]
    public function openModal(): void
    {
        $this->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->validate();

        Customer::query()->create([
            'type'  => 'customer',
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->modal = false;
    }
}
