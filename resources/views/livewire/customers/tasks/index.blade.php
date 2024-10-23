<div>
    <livewire:customers.tasks.create :$customer />

    <hr class="border-dashed border-gray-700 my-4">

    <div class="uppercase font-bold text-gray-600 text-xs mb-2">
        Pending [{{ $this->notDoneTasks->count() }}]
    </div>

    <ul class="flex flex-col gap-2" wire:sortable="updateTaskOrder" wire:sortable.options="{ animation: 100 }">
        @foreach ($this->notDoneTasks as $task)
            <li wire:sortable.item="{{ $task->id }}" wire:key="task-{{ $task->id }}">
                <livewire:customers.tasks.edit :$task wire:key="task-edit-component-{{ $task->id }}">
            </li>
        @endforeach
    </ul>

    <hr class="border-dashed border-gray-700 my-4">

    <div x-data="{ show: true }">
        <div class="uppercase font-bold text-gray-600 text-xs mb-2 flex items-center gap-2 justify-between">
            <span>Done [{{ $this->doneTasks->count() }}]</span>

            <button title="Hide/Show list" @click="show = !show" type="button">
                <x-icon x-show="!show" name="o-chevron-up" class="w-4 h-4 -mt-1 opacity-50 hover:opacity-100 hover:text-primary" />
                <x-icon x-show="show" name="o-chevron-down" class="w-4 h-4 -mt-1 opacity-50 hover:opacity-100 hover:text-primary" />
            </button>
        </div>

        <ul class="flex flex-col gap-2" x-show="show" x-transition>
            @foreach ($this->doneTasks as $task)
                <li class="flex items-start gap-2 justify-between">
                    <div class="flex gap-2 items-center">
                        <input id="task-{{ $task->id }}" type="checkbox" value="1" wire:click="toggleCheck({{ $task }}, 'pending' )" @if ($task->done_at) checked @endif />

                        <label for="task-{{ $task->id }}">
                            {{ $task->title }}
                        </label>

                        @if ($task->assignedTo)
                            <div class="text-xs italic opacity-30">
                                Assigned to: {{ $task->assignedTo->name }}
                            </div>
                        @endif
                    </div>
                    <button title="Delete task" wire:click="deleteTask({{ $task }})">
                        <x-icon name="o-trash" class="w-4 h-4 -mt-1 opacity-30 hover:opacity-100 hover:text-error" />
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
