<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Customer $customer = null;

    public function render()
    {
        return view('livewire.customers.archive');
    }

    public function archive(): void
    {
        $this->customer->delete();

        $this->success('Customer archived successfully.');
    }
}
