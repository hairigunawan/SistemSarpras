@props(['label', 'value' => null])

<div class="flex justify-between border-b pb-2">
    <span class="font-semibold text-gray-700">{{ $label }}</span>
    
    <span class="text-gray-900 text-right">
        {{ $value ?? $slot }}
    </span>
</div>