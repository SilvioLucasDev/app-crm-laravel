<div>
    <x-header title="Users" subtitle="All system users" separator />
    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('cell_id', $user)
            <strong>{{ $user->id }}</strong>
        @endscope

        @scope('cell_name', $user)
            {{ $user->name }}
        @endscope

        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-info" />
            @endforeach
        @endscope

        @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $this->users->links() }}
    </div>
</div>
