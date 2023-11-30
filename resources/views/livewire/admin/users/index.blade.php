<div>
    <x-header title="Users" subtitle="All system users" separator class="mb-2" />
    <div class="mb-4 flex space-x-4">
        <div class="w-1/3">
            <x-input label="Search by email or name" icon="o-magnifying-glass" placeholder="Search..."
                wire:model.live="search" />
        </div>

        <x-choices label="Search by permissions" wire:model.live="search_permissions" :options="$permissionsToSearch"
            option-label="key" search-function="filterPermissions" no-result-text="Ops! Nothing here ..." searchable />

        <x-select label="Records per page" wire:model.live="perPage" :options="[
            ['id' => 5, 'name' => 5],
            ['id' => 10, 'name' => 10],
            ['id' => 15, 'name' => 15],
            ['id' => 25, 'name' => 25],
            ['id' => 50, 'name' => 50],
        ]" />

        <x-checkbox label="Show deleted users" wire:model.live="search_trash" class="checkbox-primary" right tight />
    </div>

    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('header_id', $header)
            <x-table.th :$header name="id" />
        @endscope

        @scope('header_name', $header)
            <x-table.th :$header name="name" />
        @endscope

        @scope('header_email', $header)
            <x-table.th :$header name="email" />
        @endscope

        @scope('cell_id', $user)
            <strong>{{ $user->id }}</strong>
        @endscope

        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-info" />
            @endforeach
        @endscope

        @scope('actions', $user)
            @unless ($user->trashed())
                <x-button id="delete-btn-{{ $user->id }}" wire:key="delete-btn-{{ $user->id }}" icon="o-trash"
                    wire:click="destroy('{{ $user->id }}')" spinner class="btn-sm btn-error btn-ghost" />
            @else
                <x-button icon="o-arrow-path-rounded-square" wire:click="delete({{ $user->id }})" spinner
                    class="btn-sm btn-success btn-ghost" />
            @endunless
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $this->users->links(data: ['scrollTo' => false]) }}
    </div>

    <livewire:admin.users.delete />
</div>
