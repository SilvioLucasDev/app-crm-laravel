<div>
    <x-header title="Users" subtitle="All system users" separator class="mb-2" />
    <div class="mb-4 flex space-x-4 items-center">
        <div class="w-4/12">
            <x-input label="Search by email or name" icon="o-magnifying-glass" placeholder="Search..."
                wire:model.live="search" />
        </div>

        <div class="w-4/12">
            <x-choices label="Search by permissions" wire:model.live="search_permissions" :options="$permissionsToSearch"
                option-label="key" search-function="filterPermissions" no-result-text="Ops! Nothing here ..."
                searchable />
        </div>

        <div class="w-2/12">
            <x-select label="Per page" wire:model.live="perPage" :options="[
                ['id' => 5, 'name' => 5],
                ['id' => 10, 'name' => 10],
                ['id' => 15, 'name' => 15],
                ['id' => 25, 'name' => 25],
                ['id' => 50, 'name' => 50],
            ]" />
        </div>

        <div class="w-2/12">
            <x-checkbox label="Deleted users" wire:model.live="search_trash" class="checkbox-primary" right tight />
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->items">
        @scope('header_id', $header)
            <x-table.th :$header name="id" />
        @endscope

        @scope('header_name', $header)
            <x-table.th :$header name="name" />
        @endscope

        @scope('header_email', $header)
            <x-table.th :$header name="email" />
        @endscope

        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-info" />
            @endforeach
        @endscope

        @scope('actions', $user)
            <div class="flex items-centes space-x-1">
                <x-button id="show-btn-{{ $user->id }}" wire:key="show-btn-{{ $user->id }}" icon="o-pencil"
                    wire:click="show('{{ $user->id }}')" spinner class="btn-sm btn-ghost" />

                @can(\App\Enums\Can::BE_AN_ADMIN->value)
                    @unless ($user->trashed())
                        @unless ($user->is(auth()->user()))
                            <x-button id="delete-btn-{{ $user->id }}" wire:key="delete-btn-{{ $user->id }}" icon="o-trash"
                                wire:click="destroy('{{ $user->id }}')" spinner class="btn-sm btn-ghost" />

                            <x-button id="impersonate-btn-{{ $user->id }}" wire:key="impersonate-btn-{{ $user->id }}"
                                icon="o-eye" wire:click="impersonate('{{ $user->id }}')" spinner class="btn-sm btn-ghost" />
                        @endunless
                    @else
                        <x-button id="restore-btn-{{ $user->id }}" wire:key="restore-btn-{{ $user->id }}"
                            icon="o-arrow-path-rounded-square" wire:click="restore({{ $user->id }})" spinner
                            class="btn-sm btn-success btn-ghost" />
                    @endunless
                @endcan
            </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $this->items->links(data: ['scrollTo' => false]) }}
    </div>

    <livewire:admin.users.delete />
    <livewire:admin.users.restore />
    <livewire:admin.users.show />
    <livewire:admin.users.impersonate />
</div>
