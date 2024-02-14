<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.archive');
    }

    #[On('customer::archiving')]
    public function openConfirmationFor(int $customerId): void
    {
        $this->customer = Customer::select('id', 'name')->find($customerId);
        $this->modal    = true;
    }

    public function archive(): void
    {
        $this->customer->delete();

        $this->dispatch('customer::archived');
        $this->reset('modal');
        $this->success('Customer archived successfully.');
    }
}
