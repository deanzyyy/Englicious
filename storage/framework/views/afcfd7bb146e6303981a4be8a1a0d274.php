

<?php $__env->startSection('content'); ?>
<div class="container py-10">
    <h1 class="text-2xl font-bold text-white mb-6">Review Essay Answers - <?php echo e($exercise->title); ?></h1>
    <div class="mb-6 flex flex-wrap items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-4 items-center mb-0">
            <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" placeholder="Search student..." class="p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none">
            <select name="status" class="p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none">
                <option value="">All</option>
                <option value="reviewed" <?php if(($status ?? '')==='reviewed'): ?> selected <?php endif; ?>>Reviewed</option>
                <option value="unreviewed" <?php if(($status ?? '')==='unreviewed'): ?> selected <?php endif; ?>>Unreviewed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90">Filter</button>
        </form>
        <div class="flex flex-wrap gap-4 items-center">
            <a href="<?php echo e(route('classroom.exercise.review.export', ['className' => $classroom->name, 'exerciseId' => $exercise->id])); ?>" class="px-4 py-2 bg-none border-2 border-pink-500 text-pink-500 rounded hover:bg-pink-500 hover:text-white transition">Export to PDF</a>
        </div>
    </div>
    <?php if(session('success')): ?>
         <!-- Disabled success alert for debugging -->
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="text-red-400 mb-2 whitespace-pre-line"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('classroom.exercise.review.submit', ['className' => $classroom->name, 'exerciseId' => $exercise->id])); ?>" x-data="{ locked: false }">
        <?php echo csrf_field(); ?>
        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div x-data="{ open: false }" class="border border-pink-500/20 rounded-lg bg-[#211F27]">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 focus:outline-none">
                        <span class="text-lg font-semibold text-pink-500"><?php echo e($submission->user->name ?? 'Unknown'); ?></span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-pink-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-6">
                        <table class="min-w-full bg-[#18161d] rounded-lg mt-2">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-300">Essay Question</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Answer</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Score</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Comment</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Lock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $essayQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $answer = isset($submission->answers[$question->id]) && trim($submission->answers[$question->id]) !== ''
                                            ? $submission->answers[$question->id]
                                            : '<span class="italic text-gray-500">(No answer provided)</span>';
                                        $score = isset($submission->essay_scores[$question->id]) ? $submission->essay_scores[$question->id] : '';
                                        $comment = isset($submission->essay_comments[$question->id]) ? $submission->essay_comments[$question->id] : '';
                                    ?>
                                    <?php if($loop->first): ?>
                                        <input type="hidden" name="submission_ids[<?php echo e($submission->user_id); ?>]" value="<?php echo e($submission->id); ?>">
                                    <?php endif; ?>
                                    <tr x-data="{ locked: false }">
                                        <td class="px-4 py-2 text-white"><?php echo e($question->question_text); ?></td>
                                        <td class="px-4 py-2 text-gray-200"><?php echo $answer; ?></td>
                                        <td class="px-4 py-2">
                                            <input type="number" name="scores[<?php echo e($question->id); ?>][<?php echo e($submission->user_id); ?>]" min="0" max="100" value="<?php echo e($score); ?>" class="w-20 p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none" placeholder="Score" :readonly="locked">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" name="comments[<?php echo e($question->id); ?>][<?php echo e($submission->user_id); ?>]" value="<?php echo e($comment); ?>" class="w-full p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none" placeholder="Comment" :readonly="locked">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="locked = !locked" :aria-label="locked ? 'Unlock scoring and comment' : 'Lock scoring and comment'" class="p-2 rounded-full bg-gradient-to-r from-pink-500 to-orange-500 text-white focus:outline-none">
                                                <svg x-show="locked" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a2 2 0 100-4 2 2 0 000 4zm6-6V9a6 6 0 10-12 0v2a2 2 0 00-2 2v7a2 2 0 002 2h12a2 2 0 002-2v-7a2 2 0 00-2-2z" />
                                                </svg>
                                                <svg x-show="!locked" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11V9a5 5 0 00-10 0v2m12 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h10a2 2 0 012 2z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-gray-400">No submissions found.</div>
            <?php endif; ?>
        </div>
        <?php if($submissions->count() > 0): ?>
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-pink-500 to-orange-500 text-white font-semibold hover:opacity-90 transition">Save Review</button>
        </div>
        <?php endif; ?>
    </form>
    <div class="mt-8">
        <?php echo e($submissions->appends(request()->except('page'))->links()); ?>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/review-essay.blade.php ENDPATH**/ ?>