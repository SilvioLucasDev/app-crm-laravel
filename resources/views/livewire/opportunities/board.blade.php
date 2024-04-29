<div class="grid grid-cols-3 gap-4 h-full">
    @foreach ($this->opportunities as $status => $items)
        <div class="bg-base-200 p-2 rounded-md">
            <x-header :title="$status" subtitle="Total {{ $items->count() }} opportunities" class="px-2 mb-0"
                size="text-xl" separator progress-indicator />

            <div class="space-y-2 px-2">
                @foreach ($items as $item)
                    <x-card>{{ $item->title }}</x-card>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
