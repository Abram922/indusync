<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen md:flex" x-data="{open: true}">
            <!--Sidebar -->
            <aside 
            :class="{'-translate-x-full': !open}" 
            class="transform transition-transform bg-blue-800 text-white w-64 px-4 py-4">        
               <div class="flex items-center justify-between px-2">
                <div class="flex items-center space-x-2">
                    <a href="">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-100" />
                    </a>
                    <span class="text-2xl font-extrabold">Admin</span>                    
                </div>

                {{-- <p x-text="open ? 'Sidebar is open' : 'Sidebar is closed'"></p> --}}


                <button type="button" @click="open = !open" class="hidden md:block inline-flex p-2 items-center justify-center rounded-md text-blue-100 hover:bg-blue-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
               </div>
               <!-- Nav Links-->
               <nav>
                
               </nav>
            </aside>
             <!-- Main Content -->
             <main>
                <nav class="bg-blue-900 shadow-lg">
                    <div class="mx-auto px-2 sm:px-6 lg:px-8">
                        <div class="relative flex items-center justify-between md:justify-end h-16 ">
                            <div class="absolute inset-y-0 left-0 flex items-center">
                                <!-- mobile button -->
                                <button type="button" @click="open = !open" @click.away="open = false" class="inline-flex items-center justify-center
                                p-2 rounded-md text-blue-100 hover:bg-blue-700 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>                                  
                            </button>
                            </div> 
                        </div>
                    </div>
                </nav>
                <div>
                    {{ $slot }}
                </div>
             </main>
        </div>
    </body>
</html>
