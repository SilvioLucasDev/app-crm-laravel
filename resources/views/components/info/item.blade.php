@props(['label', 'value'])

<div>
    <div {{ $attributes->merge(['class' => 'text-gray-600 text-xs font-bold']) }}>{{ $label }}</div>
    <div>{{ $value }}</div>
</div>
