<div>
    @if ($editing)
        <form wire:submit="edit">
            <x-input class="input-xs" wire:model="task.title">
                <x-slot:append>
                    <x-button label="{{ 'Edit' }}" class="btn-primary btn-xs rounded-s-none" type="submit" />
                </x-slot:append>
            </x-input>
        </form>
    @else
        <div class="flex items-start gap-2 justify-between">
            <div>
                <button wire:sortable.handle title="Drand and reorder" class="cursor-grap">
                    <x-icon name="o-bars-3" class="w-4 h-4 -mt-1 opacity-30 hover:opacity-100" />
                </button>

                <input id="task-{{ $task->id }}" type="checkbox" value="1" wire:click="toggleCheck('done')" @if ($task->done_at) checked @endif />

                <label for="task-{{ $task->id }}">
                    {{ $task->title }}
                </label>

                <select>
                    <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                </select>
            </div>
            <div class="flex items-start gap-2">
                <button title="Edit task" wire:click="$set('editing', true)">
                    <x-icon name="o-pencil" class="w-4 h-4 -mt-1 opacity-30 hover:opacity-100 hover:text-primary" />
                </button>

                <button title="Delete task" wire:click="delete()">
                    <x-icon name="o-trash" class="w-4 h-4 -mt-1 opacity-30 hover:opacity-100 hover:text-error" />
                </button>
            </div>
        </div>
    @endif
</div>
