<button {{ $attributes->merge(['type' => 'submit', 'class' => 'rpc-button w-full']) }}>
    {{ $slot }}
</button>
