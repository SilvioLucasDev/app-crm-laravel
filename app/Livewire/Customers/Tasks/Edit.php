<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public Task $task;

    public bool $editing = false;

    public function rules(): array
    {
        return [
            'task.title' => ['required', 'string', 'max:255'],
        ];
    }

    public function render(): View
    {
        return view('livewire.customers.tasks.edit');
    }

    public function edit(): void
    {
        $this->validate();
        $this->task->save();

        $this->dispatch('task::updating');
        $this->success('Task updated successfully.');
        $this->reset('editing');
    }

    public function toggleCheck(string $status): void
    {
        $status === 'done' ? $this->task->update(['done_at' => now()]) : $this->task->update(['done_at' => null]);
        $this->dispatch('task::updating');
    }

    public function delete(): void
    {
        $this->task->delete();
        $this->dispatch('task::deleting');
    }
}
