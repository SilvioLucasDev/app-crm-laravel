<div>
    <x-header title="Customers" subtitle="All system customers" separator class="mb-2" />
    <div class="mb-4 flex items-end justify-between">
        <div class="flex space-x-4 w-10/12">
            <div class="w-4/12">
                <x-input label="Search by email or name" icon="o-magnifying-glass" placeholder="Search..."
                    wire:model.live="search" />
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
        </div>

        <x-button wire:click="create()" label="New Customer" icon="o-plus" spinner />
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

        @scope('actions', $customer)
            <div class="flex items-centes space-x-1">
                @unless ($customer->trashed())
                    <x-button id="archive-btn-{{ $customer->id }}" wire:key="archive-btn-{{ $customer->id }}" icon="o-trash"
                        wire:click="archive('{{ $customer->id }}')" spinner class="btn-sm btn-ghost" />
                @else
                    <x-button id="restore-btn-{{ $customer->id }}" wire:key="restore-btn-{{ $customer->id }}"
                        icon="o-arrow-path-rounded-square" wire:click="restore({{ $customer->id }})" spinner
                        class="btn-sm btn-success btn-ghost" />
                @endunless
            </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $this->items->links(data: ['scrollTo' => false]) }}
    </div>

    <livewire:customers.create />
    <livewire:customers.archive />
</div>
