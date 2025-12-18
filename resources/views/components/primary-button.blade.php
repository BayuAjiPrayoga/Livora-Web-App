<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-livora-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-livora-primary focus:bg-livora-primary active:bg-livora-primary focus:outline-none focus:ring-2 focus:ring-livora-accent focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
