<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-etno-cream border-2 border-etno-clay/40 rounded-4 font-semibold text-xs text-etno-brown uppercase tracking-widest shadow-sm hover:bg-etno-sand focus:outline-none focus:ring-2 focus:ring-etno-terracotta focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
