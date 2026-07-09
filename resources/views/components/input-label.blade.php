@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-etno-brown']) }}>
    {{ $value ?? $slot }}
</label>
