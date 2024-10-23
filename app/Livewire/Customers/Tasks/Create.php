<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Customer $customer;

    #[Rule(['required', 'string', 'max:255'])]
    public ?String $task = null;

    public function render(): View
    {
        return view('livewire.customers.tasks.create');
    }

    public function save(): void
    {
        $this->validate();

        $this->customer->tasks()->create([
            'title' => $this->task,
        ]);

        $this->task = null;
        $this->dispatch('task::creating');
        $this->success('Task created successfully.');
        $this->reset('task');
    }
}
