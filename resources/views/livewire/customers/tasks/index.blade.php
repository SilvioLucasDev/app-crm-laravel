<div>
    <livewire:customers.tasks.create :$customer />

    <hr class="border-dashed border-gray-700 my-4">

    <h2 class="uppercase font-bold text-gray-600 text-xs mb-2">
        Pending [{{ $this->notDoneTasks->count() }}]
    </h2>

    <ul class="flex flex-col gap-2">
        @foreach ($this->notDoneTasks as $task)
            <li>
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
