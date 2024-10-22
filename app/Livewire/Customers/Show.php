<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class Show extends Component
{
    public Customer $customer;

    public string $tab = 'opportunities';

    public function mount(): void
    {
        abort_unless(in_array($this->tab, ['notes', 'tasks', 'opportunities']), 404);
    }

    public function render()
    {
        return view('livewire.customers.show');
    }
}
