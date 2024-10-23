<?php

namespace App\Livewire\Customers\Tasks;

use App\Actions\DataSort;
use App\Models\{Customer, Task};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;

class Index extends Component
{
    public Customer $customer;

    #[On('task::creating')]
    #[On('task::updating')]
    #[On('task::deleting')]
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
        (new DataSort('tasks', $tasks, 'value'))->run();
    }

    public function toggleCheck(Task $task, string $status): void
    {
        $status === 'done' ? $task->update(['done_at' => now()]) : $task->update(['done_at' => null]);
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}
