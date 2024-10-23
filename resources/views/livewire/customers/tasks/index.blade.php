<div>
    <livewire:customers.tasks.create :$customer />

    <hr class="border-dashed border-gray-700 my-4">

    <h2 class="uppercase font-bold text-gray-600 text-xs mb-2">
        Pending [{{ $this->notDoneTasks->count() }}]
    </h2>

    <ul class="flex flex-col gap-2" wire:sortable="updateTaskOrder" wire:sortable.options="{ animation: 100 }">
        @foreach ($this->notDoneTasks as $task)
            <li wire:sortable.item="{{ $task->id }}" wire:key="task-{{ $task->id }}">
                <button wire:sortable.handle title="Drand and reorder" class="cursor-grap">
                    <x-icon name="o-bars-3" class="w-4 h-4 -mt-1 opacity-30" />
                </button>

                <input id="task-{{ $task->id }}" type="checkbox" value="1"
                    @if ($task->done_at) checked @endif />

                <label for="task-{{ $task->id }}">
                    {{ $task->title }}
                </label>

                <select>
                    <option>Assigned to: {{ $task->assignedTo?->name }}</option>
                </select>
            </li>
        @endforeach
    </ul>

    <hr class="border-dashed border-gray-700 my-4">

    <h2 class="uppercase font-bold text-gray-600 text-xs mb-2">
        Done tasks [{{ $this->doneTasks->count() }}]
    </h2>

    <ul class="flex flex-col gap-2">
        @foreach ($this->doneTasks as $task)
            <li class="flex gap-2">
                <input id="task-{{ $task->id }}" type="checkbox" value="1"
                    @if ($task->done_at) checked @endif />

                <label for="task-{{ $task->id }}">
                    {{ $task->title }}
                </label>

                <div>
                    Assigned to: {{ $task->assignedTo?->name }}
                </div>
            </li>
        @endforeach
    </ul>
</div>
