<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\{Task, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public Task $task;

    public bool $editing = false;

    public mixed $selectedUser = null;

    #[Computed]
    public function users(): Collection
    {
        return User::all();
    }

    public function rules(): array
    {
        return [
            'task.title'   => ['required', 'string', 'max:255'],
            'selectedUser' => ['nullable', 'exists:users,id'],
        ];
    }

    public function mount(): void
    {
        $this->selectedUser = $this->task->assigned_to;
    }

    public function render(): View
    {
        return view('livewire.customers.tasks.edit');
    }

    public function edit(): void
    {
        $this->validate();

        if ($this->selectedUser) {
            $this->task->assigned_to = $this->selectedUser;
        }

        $this->task->save();

        if ($this->task->wasChanged('assigned_to')) {
            $this->task->load('assignedTo');
        }

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
