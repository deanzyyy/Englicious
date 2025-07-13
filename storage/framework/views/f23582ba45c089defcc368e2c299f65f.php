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
            <a href="<?php echo e(route('exercises.index')); ?>" 
               class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Exercise List
            </a>

            <!-- Score Card -->
            <div class="bg-[#211F27] rounded-lg overflow-hidden shadow-lg border border-pink-500/20">
                <!-- Header -->
                <div class="p-6 border-b border-gray-700">
                    <h1 class="text-2xl font-bold text-white mb-2">Exercise Results</h1>
                    <p class="text-gray-400"><?php echo e($exercise->title); ?></p>
                </div>

                <!-- Score Summary -->
                <div class="p-6 bg-gradient-to-r from-pink-500/10 to-orange-500/10">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-white mb-1">Your Score</h2>
                            <p class="text-gray-400">Completed on <?php echo e($submission->created_at->format('F j, Y, g:i a')); ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold text-pink-500"><?php echo e($recalculatedScore ?? $submission->score); ?>%</div>
                            <?php
                                $score = $recalculatedScore ?? $submission->score ?? 0;
                                $motivasi = 'Nice Try!';
                                $motivasiColor = 'text-orange-400';
                                if ($score >= 80) {
                                    $motivasi = 'Exellent!';
                                    $motivasiColor = 'text-green-400';
                                } elseif ($score >= 60) {
                                    $motivasi = 'Good Job!';
                                    $motivasiColor = 'text-pink-400';
                                }
                            ?>
                            <div class="mt-2 text-lg font-bold <?php echo e($motivasiColor); ?>"><?php echo e($motivasi); ?></div>
                            <div class="text-xs text-gray-400 mt-1">* Score final</div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Results -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Question Details</h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $exercise->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-[#2A2A32] rounded-lg p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <?php if($question->type === 'optional'): ?>
                                            <?php if(isset($submission->answers[$question->id]) && $submission->answers[$question->id] == $question->correct_answer): ?>
                                                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        <?php elseif($question->type === 'essay'): ?>
                                            <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <?php if($question->image_path): ?>
                                            <div class="mb-3">
                                                <img src="<?php echo e(asset('storage/' . $question->image_path)); ?>" 
                                                     alt="Question Image" 
                                                     class="max-w-full h-auto rounded-lg">
                                            </div>
                                        <?php endif; ?>

                                        <p class="text-white mb-3"><?php echo e($question->question_text); ?></p>

                                        <?php if($question->type === 'optional'): ?>
                                            <div class="grid grid-cols-2 gap-3">
                                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionIndex => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex items-center space-x-2">
                                                        <?php if($optionIndex == $question->correct_answer): ?>
                                                            <span class="text-green-500">✓</span>
                                                        <?php elseif(isset($submission->answers[$question->id]) && $submission->answers[$question->id] == $optionIndex): ?>
                                                            <span class="text-red-500">✗</span>
                                                        <?php endif; ?>
                                                        <span class="text-gray-400 <?php echo e($optionIndex == $question->correct_answer ? 'text-green-500' : ''); ?>">
                                                            <?php echo e($option); ?>

                                                        </span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php elseif($question->type === 'essay'): ?>
                                            <div class="mt-2">
                                                <div class="mb-1 text-gray-400">Jawaban Anda:</div>
                                                <div class="bg-[#18161d] text-white rounded-lg p-3"><?php echo e($submission->answers[$question->id] ?? '-'); ?></div>
                                                <div class="mt-2">
                                                    <?php if(isset($submission->essay_scores[$question->id])): ?>
                                                        <span class="text-green-400 font-semibold">Nilai: <?php echo e($submission->essay_scores[$question->id]); ?></span>
                                                        <?php if(isset($submission->essay_comments[$question->id])): ?>
                                                            <div class="text-gray-400 mt-1">Komentar: <?php echo e($submission->essay_comments[$question->id]); ?></div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-orange-400 font-semibold">Menunggu penilaian</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-gray-700 flex justify-end space-x-4">
                    <a href="<?php echo e(route('exercises.take.general', ['id' => $exercise->id])); ?>" 
                       class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                        Try Again
                    </a>
                    <a href="<?php echo e(route('exercises.index')); ?>" 
                       class="px-4 py-2 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-colors">
                        Back to Exercises
                    </a>
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
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/exercises/result.blade.php ENDPATH**/ ?>