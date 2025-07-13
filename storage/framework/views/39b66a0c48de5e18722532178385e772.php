<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="py-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-gray-500 text-xl">ASSIGNMENTS</h1>
            <?php if(Auth::check() && Auth::user()->role == 'teacher'): ?>
            <a href="<?php echo e(route('classroom.assignments.create', $classroom->name)); ?>" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-all text-sm font-medium uppercase tracking-wider">Create Assignment</a>
            <?php endif; ?>
        </div>
        <div class="space-y-5">
            <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card w-full h-auto p-5 bg-[#211F27] rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 class="text-white font-bold text-lg mb-0"><?php echo e($assignment->title); ?></h2>
                        <?php if(Auth::check() && Auth::user()->role === 'student'): ?>
                            <?php
                                $submitted = $assignment->submissions->where('user_id', Auth::id())->first();
                            ?>
                            <?php if($submitted): ?>
                                <span class="flex items-center gap-1 text-green-500 font-semibold text-sm ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.414 7.414a1 1 0 01-1.414 0l-3.414-3.414a1 1 0 111.414-1.414L8 11.586l6.707-6.707a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    assignment has been sent
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('classroom.assignments.show', [$classroom->name, $assignment->id])); ?>" class="rounded-lg px-4 py-2 text-gray-300 border border-transparent hover:border-pink-500 hover:text-pink-500 hover:bg-pink-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">Open</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="card w-full h-auto p-5 bg-[#211F27] rounded-lg flex items-center justify-center">
                    <p class="text-gray-400">Belum ada assignment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/assignments/index.blade.php ENDPATH**/ ?>