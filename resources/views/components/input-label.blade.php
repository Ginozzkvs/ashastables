@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm', 'style' => 'color: #d4af37;']) }}>
    {{ $value ?? $slot }}
</label>
