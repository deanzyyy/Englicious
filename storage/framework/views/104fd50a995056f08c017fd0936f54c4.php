

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-8 py-6 max-w-[1200px]">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">News Management</h1>
        <a href="<?php echo e(route('admin.news.create')); ?>" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">+ Add News</a>
    </div>
    <div class="mb-4 text-gray-300">Total News: <span class="font-bold text-pink-400"><?php echo e($total); ?></span></div>
    <?php if(session('success')): ?>
        <div class="bg-green-600 text-white px-4 py-2 rounded mb-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-[#211F27] rounded-xl shadow-lg border border-pink-500/20 p-5 flex flex-col h-full">
            <?php if($item->image): ?>
                <img src="<?php echo e(asset('storage/'.$item->image)); ?>" alt="" class="w-full h-40 object-cover rounded-lg mb-4 border border-pink-400/30">
            <?php else: ?>
                <div class="w-full h-40 flex items-center justify-center bg-pink-500/10 text-pink-400 rounded-lg mb-4">No Image</div>
            <?php endif; ?>
            <h2 class="text-lg font-bold text-white mb-2"><?php echo e($item->title); ?></h2>
            <p class="text-gray-300 text-sm mb-2 line-clamp-3"><?php echo e($item->description); ?></p>
            <div class="flex items-center text-xs text-gray-400 mb-2">
                <i class="fi fi-rr-calendar mr-1"></i> <?php echo e(Carbon\Carbon::parse($item->date)->format('d M Y')); ?>

            </div>
            <div class="flex items-center gap-4 text-pink-400 mb-4">
                <span><i class="fi fi-rr-heart mr-1"></i> <?php echo e($item->likes); ?></span>
                <span><i class="fi fi-rr-eye mr-1"></i> <?php echo e($item->views); ?></span>
                <span class="ml-auto text-xs text-gray-400">By: <?php echo e($item->creator->name ?? '-'); ?></span>
            </div>
            <div class="flex gap-2 mt-auto">
                <a href="<?php echo e(route('admin.news.edit', $item->id)); ?>" class="flex-1 bg-gradient-to-r from-pink-500 to-orange-500 text-white py-2 rounded-lg text-center font-semibold hover:from-pink-600 hover:to-orange-600 transition">Edit</a>
                <form action="<?php echo e(route('admin.news.destroy', $item->id)); ?>" method="POST" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full border border-pink-500 text-pink-500 py-2 rounded-lg font-semibold bg-transparent hover:bg-pink-500/10 transition" onclick="return confirm('Delete this news?')">Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($news->links()); ?></div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/admin/news/index.blade.php ENDPATH**/ ?>