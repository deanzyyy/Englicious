<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" id="html-root">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Englicious')); ?></title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

  </head>
  <body class="font-sans antialiased bg-[#101014] text-white">
    <div x-data="{ isSidebarOpen: true }" class="flex min-h-screen">
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
        <main class="flex-1 transition-all duration-300" :class="{ 'ml-64': isSidebarOpen, 'ml-16': !isSidebarOpen }">
            <?php if(session('error')): ?>
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg fixed top-4 right-4 z-50">
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>
            <?php echo e($slot); ?>

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
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

  </body>
</html><?php /**PATH D:\Englicious\Englicious\resources\views/components/layout.blade.php ENDPATH**/ ?>