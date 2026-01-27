<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-2 border font-semibold text-sm uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-80', 'style' => 'background: transparent; border-color: #d4af37; color: #d4af37; border-radius: 0;']) }}>
    {{ $slot }}
</button>
