<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;

class Index extends Component
{
    public Customer $customer;

    #[On('task::created')]
    public function render(): View
    {
        return view('livewire.customers.tasks.index');
    }

    #[Computed]
    public function notDoneTasks(): Collection
    {
        return $this->customer->tasks()->with('assignedTo')->notDone()->get();
    }

    #[Computed]
    public function doneTasks(): Collection
    {
        return $this->customer->tasks()->with('assignedTo')->done()->get();
    }
}
