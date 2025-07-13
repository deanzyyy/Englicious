<?php if (isset($component)) { $__componentOriginal1f9e5f64f242295036c059d9dc1c375c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c = $attributes; } ?>
<?php $component = App\View\Components\Layout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="w-full max-w-6xl mx-auto p-8 px-0">
        <a href="<?php echo e(route('exercises.index')); ?>" class="inline-flex items-center text-gray-400 hover:text-pink-500 mb-6 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Exercise List
        </a>
        <h1 class="text-2xl font-bold mb-6">My Exercise Results</h1>
        <table class="w-full text-left bg-[#211F27] rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gradient-to-r from-pink-500 to-orange-500 text-white">
                    <th class="py-3 px-4">Exercise</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Score</th>
                    <th class="py-3 px-4"></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-700 hover:bg-[#2a2833] transition-all">
                        <td class="py-3 px-4 text-white"><?php echo e($submission->exercise->title ?? '-'); ?></td>
                        <td class="py-3 px-4 text-gray-400"><?php echo e($submission->created_at->format('d M Y H:i')); ?></td>
                        <td class="py-3 px-4 text-pink-500 font-bold"><?php echo e(round($submission->score, 2)); ?>%</td>
                        <td class="py-3 px-4">
                            <a href="<?php echo e(route('exercises.result', array_filter(['id' => $submission->exercise_id, 'classroom_id' => $submission->classroom_id]))); ?>" class="text-pink-500 underline hover:text-orange-500">View</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-6 px-4 text-center text-gray-400">No exercise results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $attributes = $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $component = $__componentOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/exercises/my_results.blade.php ENDPATH**/ ?>