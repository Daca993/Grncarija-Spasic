@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 text-center text-lg font-medium text-etno-dark bg-etno-sand/80 rounded-4 border-2 border-etno-terracotta focus:outline-none transition'
            : 'block w-full px-4 py-3 text-center text-lg font-medium text-etno-brown hover:text-etno-dark hover:bg-etno-sand/50 rounded-4 border-2 border-transparent hover:border-etno-clay/50 focus:outline-none transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
