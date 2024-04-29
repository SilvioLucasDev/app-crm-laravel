<div class="grid grid-cols-3 gap-4 h-full" wire:sortable-group="updateOpportunities">
    @foreach ($this->opportunities as $status => $items)
        <div class="bg-base-200 p-2 rounded-md" wire:key="group-{{ $status }}">
            <x-header :title="$status" subtitle="Total {{ $items->count() }} opportunities" class="px-2 mb-0"
                size="text-xl" separator progress-indicator />

            <div class="space-y-2 px-2" wire:sortable-group.item-group="{{ $status }}"
                wire:sortable-group.options="{ animation: 100 }">
                @foreach ($items as $item)
                    <x-card class="hover:opacity-60 cursor-grab" wire:sortable-group.handle
                        wire:sortable-group.item="{{ $item->id }}" wire:key="opportunity-{{ $item->id }}">
                        {{ $item->title }}
                    </x-card>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
