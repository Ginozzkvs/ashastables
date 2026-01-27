<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 border font-semibold text-sm uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90', 'style' => 'background: transparent; border-color: #ef4444; color: #ef4444; border-radius: 0;']) }}>
    {{ $slot }}
</button>
