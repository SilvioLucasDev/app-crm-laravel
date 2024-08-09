@props(['title'])

<div {{ $attributes->merge(['class' => 'uppercase text-sm text-gray-600 font-bold']) }}>
    {{ $title }}
</div>
<hr class="mt-1 mb-2">
