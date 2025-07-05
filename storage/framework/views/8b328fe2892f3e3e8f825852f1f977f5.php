<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Englicious')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-[#101014] min-h-screen flex">
    <?php if (isset($component)) { $__componentOriginald31f0a1d6e85408eecaaa9471b609820 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald31f0a1d6e85408eecaaa9471b609820 = $attributes; } ?>
<?php $component = App\View\Components\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $attributes = $__attributesOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $component = $__componentOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__componentOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>

    <main class="flex-1 transition-all duration-300 ml-64 p-6">
        <?php echo $__env->yieldContent('content'); ?>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH D:\Englicious\Englicious\resources\views/layouts/app.blade.php ENDPATH**/ ?>