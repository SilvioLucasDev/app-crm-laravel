<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public bool $modal = false;

    public Customer $customer;

    public function rules(): array
    {
        return [
            'customer.name'  => ['required', 'min:3', 'max:255'],
            'customer.email' => ['required_without:phone', 'email', 'max:255', 'unique:customers,email'],
            'customer.phone' => ['required_without:email', 'unique:customers,phone'],
        ];
    }

    public function render(): View
    {
        return view('livewire.customers.update');
    }

    public function save(): void
    {
        $this->validate();

        $this->customer->update();

        $this->dispatch('customer::updated');
        $this->reset('modal');
        $this->success('Customer updated successfully.');
    }
}
