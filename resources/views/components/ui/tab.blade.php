@props(['href'])

@php
    $tab = collect(explode('/', $href))->last();
    $requestTab = request('tab');
@endphp

<a {{ $attributes->class([
    'group items-center px-2 py-2 text-sm font-medium text-gray-500 rounded-t-md border-b-2 hover:text-gray-200 hover:bg-base-200',
    'text-gray-200 bg-base-200' => $tab === $requestTab,
    'border-transparent' => $tab !== $requestTab,
]) }}
    href="{{ $href }}" :wire:navigate.hover>
    {{ $slot }}
</a>
