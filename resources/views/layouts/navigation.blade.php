<nav x-data="{ open: false }" class="bg-black text-white sm:w-64 sm:relative fixed inset-y-0 left-0 z-50">
    <!-- Logo + Hamburguer -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
        <div class="flex items-center">
            <img src="{{ asset('images/logo/glowtime.png') }}" alt="GlowTime Logo" class="h-8 w-8">
            <span class="ml-3 text-xl font-extrabold">
                <span class="text-pink-500 drop-shadow-[0_0_5px_#ff00ff]">Glow</span>
                <span class="text-purple-500 drop-shadow-[0_0_5px_#9900ff]">Time</span>
            </span>
        </div>

        <!-- Botão hamburguer no mobile -->
        <button @click="open = !open" class="sm:hidden text-white focus:outline-none z-50">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Menu desktop -->
    <div class="hidden sm:block mt-6 space-y-2">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block px-6 py-2 rounded hover:bg-pink-600">
            {{ __('Painel') }}
        </x-nav-link>

        <x-nav-link :href="route('clients.index')" :active="request()->is('clients*')" class="block px-6 py-2 rounded hover:bg-pink-600">
            {{ __('Clientes') }}
        </x-nav-link>

        <x-nav-link :href="route('services.index')" :active="request()->is('services*')" class="block px-6 py-2 rounded hover:bg-pink-600">
            {{ __('Serviços') }}
        </x-nav-link>

        <x-nav-link :href="route('professionals.index')" :active="request()->is('professionals*')" class="block px-6 py-2 rounded hover:bg-pink-600">
            {{ __('Profissionais') }}
        </x-nav-link>
    </div>

    <!-- Overlay (fundo escuro) -->
    <div x-show="open" 
         x-transition.opacity
         class="fixed inset-0 bg-black bg-opacity-50 sm:hidden z-40"
         @click="open = false">
    </div>

    <!-- Menu mobile (slide animado) -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="-translate-x-full opacity-0"
         class="sm:hidden fixed inset-y-0 left-0 bg-black w-64 p-6 space-y-2 z-50">
        
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Painel') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('clients.index')" :active="request()->is('clients*')">
            {{ __('Clientes') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('services.index')" :active="request()->is('services*')">
            {{ __('Serviços') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('professionals.index')" :active="request()->is('professionals*')">
            {{ __('Profissionais') }}
        </x-responsive-nav-link>
    </div>

    <!-- Rodapé -->
    <div class="absolute bottom-0 w-full px-6 py-4 border-t border-gray-800 hidden sm:block">
        <div class="font-medium">{{ Auth::user()->name }}</div>
        <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="text-red-400 hover:text-red-600">{{ __('Sair') }}</button>
        </form>
    </div>
</nav>
