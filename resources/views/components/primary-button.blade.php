<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-etno-terracotta border border-transparent rounded-4 font-semibold text-xs text-white uppercase tracking-widest shadow-lg hover:bg-etno-rust focus:outline-none focus:ring-2 focus:ring-etno-clay focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
