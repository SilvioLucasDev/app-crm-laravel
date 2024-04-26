<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.update');
    }

    #[On('customer::updating')]
    public function loadCustomer(int $id): void
    {
        $customer = Customer::find($id);
        $this->form->setCustomer($customer);

        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->update();

        $this->dispatch('customer::updated');
        $this->reset('modal');
        $this->success('Customer updated successfully.');
    }
}
