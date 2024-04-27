<div>
    <x-header title="Opportunities" subtitle="All system opportunities" separator class="mb-4">
        <x-slot:actions class="items-end">
            <x-button wire:click="toggleFilters()" icon="o-funnel" spinner />
            <x-button wire:click="create()" label="New Opportunity" icon="o-plus" class="btn-primary" spinner />
        </x-slot:actions>
    </x-header>

    @if ($filtersVisible)
        <div class="grid grid-cols-12 mb-4">
            <div class="col-span-12 sm:col-span-10 xl:col-span-11 mb-4 sm:mb-0">
                <div class="grid grid-cols-3 gap-4 justify-items-start place-items-end">
                    <div class="col-span-3 sm:col-span-1 w-full">
                        <x-input label="Search by title" icon="o-magnifying-glass" placeholder="Search..."
                            wire:model.live="search" />
                    </div>
                    <div class="col-span-3 sm:col-span-1">
                        <x-checkbox label="Archived" wire:model.live="searchTrash" class="checkbox-primary" right
                            tight />
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-1">
                <x-select label="Per page" wire:model.live="perPage" :options="[
                    ['id' => 5, 'name' => 5],
                    ['id' => 10, 'name' => 10],
                    ['id' => 15, 'name' => 15],
                    ['id' => 25, 'name' => 25],
                    ['id' => 50, 'name' => 50],
                ]" />
            </div>
        </div>
    @endif

    <x-table :headers="$this->headers" :rows="$this->items">
        @scope('header_id', $header)
            <x-table.th :$header name="id" />
        @endscope

        @scope('header_title', $header)
            <x-table.th :$header name="title" />
        @endscope

        @scope('header_status', $header)
            <x-table.th :$header name="status" />
        @endscope

        @scope('header_amount', $header)
            <x-table.th :$header name="amount" />
        @endscope

        @scope('actions', $opportunity)
            <div class="flex items-centes space-x-1">
                @unless ($opportunity->trashed())
                    <x-button id="update-btn-{{ $opportunity->id }}" wire:key="update-btn-{{ $opportunity->id }}"
                        icon="o-pencil" wire:click="update('{{ $opportunity->id }}')" spinner class="btn-sm btn-ghost" />

                    <x-button id="archive-btn-{{ $opportunity->id }}" wire:key="archive-btn-{{ $opportunity->id }}"
                        icon="o-trash" wire:click="archive('{{ $opportunity->id }}')" spinner class="btn-sm btn-ghost" />
                @else
                    <x-button id="restore-btn-{{ $opportunity->id }}" wire:key="restore-btn-{{ $opportunity->id }}"
                        icon="o-arrow-path-rounded-square" wire:click="restore({{ $opportunity->id }})" spinner
                        class="btn-sm btn-success btn-ghost" />
                @endunless
            </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ $this->items->links(data: ['scrollTo' => false]) }}
    </div>

    <livewire:opportunities.create />
    <livewire:opportunities.update />
    <livewire:opportunities.archive />
    <livewire:opportunities.restore />
</div>
