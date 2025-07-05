<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Englicious') }}</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @livewireStyles
  </head>
  <body class="font-sans antialiased bg-[#101014] text-white">
    <div x-data="{ isSidebarOpen: true }" class="flex min-h-screen">
        <x-sidebar />
        <main class="flex-1 transition-all duration-300" :class="{ 'ml-64': isSidebarOpen, 'ml-16': !isSidebarOpen }">
            @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg fixed top-4 right-4 z-50">
                {{ session('error') }}
            </div>
            @endif
            {{ $slot }}
        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast-notification" class="fixed top-4 right-4 bg-pink-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50">
        <p id="toast-message" class="font-medium"></p>
    </div>

    <script>
        // Initialize Alpine.js data and functions
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                isOpen: true,
                toggle() {
                    this.isOpen = !this.isOpen;
                    this.updateMainMargin();
                },
                updateMainMargin() {
                    const mainElement = document.querySelector('main');
                    if (mainElement) {
                        mainElement.style.marginLeft = this.isOpen ? '16rem' : '4rem';
                    }
                }
            });
        });

        // Toast notification function
        window.showToast = function(message, duration = 3000) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.classList.remove('hidden');
                
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, duration);
            }
        };

        // Ensure the correct theme class is set on <html> at page load
        (function() {
          const html = document.getElementById('html-root');
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'light') {
            html.classList.add('light');
            html.classList.remove('dark');
          } else if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
            html.classList.remove('light');
          } else {
            html.classList.add('light');
            html.classList.remove('dark');
          }
        })();
    </script>
    @stack('scripts')
    @livewireScripts
  </body>
</html>