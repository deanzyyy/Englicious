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
    <div class="min-h-screen bg-[#1a1a1f] p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <a href="<?php echo e(route('materials.index')); ?>" class="flex items-center text-gray-400 hover:text-pink-400 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Materials List
            </a>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2"><?php echo e($material->title); ?></h1>
                <div class="flex items-center space-x-4 text-gray-400">
                    <span><?php echo e($material->category); ?></span>
                    <span>•</span>
                    <span><?php echo e($material->topic->name); ?></span>
                    <span>•</span>
                    <span><?php echo e($material->subtopic->name); ?></span>
                </div>
                <?php if($material->description): ?>
                    <p class="text-gray-300 mt-4"><?php echo e($material->description); ?></p>
                <?php endif; ?>
            </div>

            <!-- PDF Viewer -->
            <div class="bg-[#211F27] rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white font-medium">PDF Document</h2>
                        <a href="<?php echo e(Storage::url($material->file_path)); ?>" target="_blank" 
                           class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="aspect-[16/9] w-full">
                    <iframe src="<?php echo e(Storage::url($material->file_path)); ?>" 
                            class="w-full h-full"
                            type="application/pdf">
                    </iframe>
                </div>
            </div>
        </div>
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
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/materials/show.blade.php ENDPATH**/ ?>