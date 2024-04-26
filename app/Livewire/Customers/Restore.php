<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.restore');
    }

    #[On('customer::restoring')]
    public function openConfirmationFor(int $customerId): void
    {
        $this->customer = Customer::select('id', 'name')->onlyTrashed()->find($customerId);
        $this->modal    = true;
    }

    public function restore(): void
    {
        $this->customer->restore();

        $this->dispatch('customer::restored');
        $this->reset('modal');
        $this->success('Customer restored successfully.');
    }
}
