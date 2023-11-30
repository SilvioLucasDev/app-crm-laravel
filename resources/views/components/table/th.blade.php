@props(['header', 'name'])

<div wire:click="sortBy('{{ $name }}', '{{ $this->sortDirection == 'asc' ? 'desc' : 'asc' }}')"
    class="cursor-pointer">
    {{ $header['label'] }}
    @if ($this->sortColumnBy == $name)
        <x-icon name="s-chevron-{{ $this->sortDirection == 'asc' ? 'down' : 'up' }}" class="h-3 w-3 ml-px" />
    @endif
</div>
