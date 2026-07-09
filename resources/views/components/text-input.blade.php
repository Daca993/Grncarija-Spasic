@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-etno-clay/40 focus:border-etno-terracotta focus:ring-etno-terracotta rounded-4 shadow-sm bg-white text-etno-dark']) }}>
