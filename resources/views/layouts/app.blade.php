<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Englicious') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            DEFAULT: '#1a1a1a',
                            lighter: '#2d2d2d'
                        },
                        pink: {
                            DEFAULT: '#FF1493',
                            dark: '#C71585'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    @stack('styles')
</head>
<body class="bg-[#101014] min-h-screen flex">
    <x-sidebar />

    <main class="flex-1 transition-all duration-300 ml-64 p-6">
        @yield('content')
    </main>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300 z-50">
        <span id="toastMessage"></span>
    </div>

    <!-- Scripts -->
    <script>
        // Fungsi untuk menangani toggle sidebar
        window.handleSidebarToggle = function(isOpen) {
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.style.marginLeft = isOpen ? '16rem' : '4rem';
            }
        };

        // Fungsi untuk menampilkan toast
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toast.classList.remove('translate-y-full', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            if (isError) {
                toast.classList.add('bg-red-500');
                toast.classList.remove('bg-gray-800');
            } else {
                toast.classList.add('bg-gray-800');
                toast.classList.remove('bg-red-500');
            }
            
            toastMessage.textContent = message;
            
            setTimeout(() => {
                toast.classList.add('translate-y-full', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html> 