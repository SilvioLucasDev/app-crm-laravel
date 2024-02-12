<div>
    <x-header title="Customers" subtitle="All system customers" separator class="mb-2" />
    <div class="mb-4 flex space-x-4">
        <div class="w-1/3">
            <x-input label="Search by email or name" icon="o-magnifying-glass" placeholder="Search..."
                wire:model.live="search" />
        </div>

        <x-select label="Records per page" wire:model.live="perPage" :options="[
            ['id' => 5, 'name' => 5],
            ['id' => 10, 'name' => 10],
            ['id' => 15, 'name' => 15],
            ['id' => 25, 'name' => 25],
            ['id' => 50, 'name' => 50],
        ]" />
    </div>

    <x-table :headers="$this->headers" :rows="$this->customers">
    </x-table>

    <div class="mt-4">
        {{ $this->customers->links(data: ['scrollTo' => false]) }}
    </div>

    <livewire:admin.users.delete />
    <livewire:admin.users.restore />
    <livewire:admin.users.show />
    <livewire:admin.users.impersonate />
</div>
