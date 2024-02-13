<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\On;
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

    #[On('customer::archiving')]
    public function openConfirmationFor(int $customerId): void
    {
        $this->customer = Customer::select('id', 'name')->find($customerId);
        $this->archive();
    }

    public function archive(): void
    {
        $this->customer->delete();

        $this->success('Customer archived successfully.');
    }
}
