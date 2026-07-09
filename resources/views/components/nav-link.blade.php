@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-etno-terracotta text-base font-medium leading-5 text-etno-dark focus:outline-none focus:border-etno-rust transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-base font-medium leading-5 text-etno-brown hover:text-etno-terracotta hover:border-etno-clay/50 focus:outline-none focus:text-etno-terracotta focus:border-etno-clay/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
