<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\{Customer, Task};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        return $this->customer->tasks()->with('assignedTo')->notDone()->orderBy('sort_order')->get();
    }

    #[Computed]
    public function doneTasks(): Collection
    {
        return $this->customer->tasks()->with('assignedTo')->done()->orderBy('sort_order')->get();
    }

    public function updateTaskOrder($tasks): void
    {
        $sortOrder = collect($tasks)->pluck('value')->join(',');

        Task::query()->update(['sort_order' => DB::raw("field(id, $sortOrder)")]);
    }
}
