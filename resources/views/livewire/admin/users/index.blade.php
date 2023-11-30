<div>
    <x-header title="Users" subtitle="All system users" separator class="mb-2" />
    <div class="mb-4 flex space-x-4">
        <div class="w-1/3">
            <x-input label="Search by email or name" icon="o-magnifying-glass" placeholder="Search..."
                wire:model.live="search" />
        </div>

        <x-choices label="Search by permissions" wire:model.live="search_permissions" :options="$permissionsToSearch"
            option-label="key" search-function="filterPermissions" no-result-text="Ops! Nothing here ..." searchable />
    </div>

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
