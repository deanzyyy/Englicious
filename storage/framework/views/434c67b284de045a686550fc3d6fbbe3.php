

<?php $__env->startSection('content'); ?>
<div class="w-full px-0 py-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Add News</h1>
    <form action="<?php echo e(route('admin.news.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6 w-full">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block font-semibold mb-1 text-white">Title</label>
            <input type="text" name="title" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" required value="<?php echo e(old('title')); ?>">
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-400 text-sm"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Image</label>
            <input type="file" name="image" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none">
            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-400 text-sm"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Description</label>
            <textarea name="description" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" rows="4" required><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-400 text-sm"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Date</label>
            <input type="date" name="date" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" required value="<?php echo e(old('date')); ?>">
            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-400 text-sm"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="flex justify-end gap-4 w-full">
            <a href="<?php echo e(route('admin.news.index')); ?>" class="border border-pink-500 text-pink-500 px-8 py-3 rounded-xl font-semibold hover:bg-pink-500/10 transition">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-xl text-white font-semibold bg-pink-500 hover:from-pink-600 hover:to-orange-600 transition">Save</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/admin/news/create.blade.php ENDPATH**/ ?>