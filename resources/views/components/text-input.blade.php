@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded-none border focus:outline-none focus:ring-1 focus:ring-yellow-600', 'style' => 'background: #0f1419; border-color: #d4af37; color: #e0e0e0;']) }}>
