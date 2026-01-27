<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 border-none font-semibold text-sm uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90', 'style' => 'background: #d4af37; color: #0f1419; border-radius: 0;']) }}>
    {{ $slot }}
</button>
