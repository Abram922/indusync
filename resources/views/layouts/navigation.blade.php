<div class="flex h-screen">
    <!-- Sidebar -->
    <nav class="w-64 bg-[#132A84] text-white">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-700">
            <a href="{{ route('dashboard') }}" style="margin-top: 30px">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="150" height="150" class="d-inline-block align-text-top">
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="py-4">
            <!-- Dashboard -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block px-6 py-3 text-white hover:bg-blue-700">
                {{ __('Dashboard') }}
            </x-nav-link>

            <!-- Inventory Section -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="block w-full text-left px-6 py-3 text-white hover:bg-blue-700">
                    {{ __('Inventory') }}
                    <span class="float-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                <!-- Inventory Submenu -->
                <div x-show="open" class="ml-6">
                    <x-nav-link :href="route('inventory.index')" class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Incoming Item Data') }}
                    </x-nav-link>
                    <x-nav-link  :href="route('inventory.outComingData')"  class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Outgoing Item Data') }}
                    </x-nav-link>
                    <x-nav-link  :href="route('inventory.stockData')" class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Stock Data') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Penjualan Section -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="block w-full text-left px-6 py-3 text-white hover:bg-blue-700">
                    {{ __('Penjualan') }}
                    <span class="float-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                <!-- Penjualan Submenu -->
                <div x-show="open" class="ml-6">
                    <x-nav-link :href="route('penjualan.inputSales')" class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Input Sales') }}
                    </x-nav-link>
                    <x-nav-link  :href="route('penjualan.salesHistory')" class="block w-full  px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Sales History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('penjualan.inputPurchase')" class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Input Purchase') }}
                    </x-nav-link>
                    <x-nav-link  :href="route('penjualan.purchaseHistory')"   class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Purchase History') }}
                    </x-nav-link>
                    <x-nav-link  :href="route('outgoing.financialRecap')"   class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Financial Recap') }}
                    </x-nav-link>
                </div>
            </div>
        </div>

        <!-- User Settings -->
        <div x-data="{ open: false }" class="mt-auto py-4 px-6 border-t border-gray-700">
            <!-- User Info -->
            <button @click="open = !open" class="block w-full text-left px-6 py-3 text-white hover:bg-blue-700">
                <div>
                    <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                </div>
                <span class="float-right">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        
            <!-- Dropdown Submenu -->
            <div x-show="open" class="ml-6 space-y-2 mt-2">
                <!-- Profile Link -->
                <x-nav-link :href="route('profile.edit')" class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                    {{ __('Profile') }}
                </x-nav-link>
        
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                    @csrf
                    <x-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block w-full px-7 py-2 text-white hover:bg-blue-600">
                        {{ __('Log Out') }}
                    </x-nav-link>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-100 p-6">
        <div>
            {{ $slot }}
        </div>
    </main>
</div>
