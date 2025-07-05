<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Englicious')); ?></title>

    <!-- Scripts and Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Additional Head Content -->
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="font-sans antialiased bg-[#101014] text-white">
    <?php echo $__env->yieldContent('content'); ?>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH D:\Englicious\Englicious\resources\views/layouts/auth.blade.php ENDPATH**/ ?>