<?php $__env->startSection('content'); ?>
<div class="mt-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white"><?php echo e($game->name); ?> - Final Results</h2>
        <p class="text-gray-400 text-lg mt-2">Game completed! Here are the final scores.</p>
    </div>

    <!-- Winner Announcement -->
    <?php if($scores->count() > 0): ?>
    <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 rounded-lg p-6 mb-6 border border-yellow-500/40">
        <div class="text-center">
            <div class="mb-4">
                <i class="fi fi-rr-trophy text-yellow-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-yellow-400 mb-2">
                🏆 Winner: <?php echo e(($scores->first()->player->team) ? $scores->first()->player->team->name : ($scores->first()->player->student_name ?? $scores->first()->player->user->name ?? 'Unknown')); ?>

            </h3>
            <p class="text-gray-300">Congratulations on achieving the highest score!</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scoreboard -->
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-white mb-6">Final Rankings</h3>
            
            <?php if($scores->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $scores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-4 rounded-lg <?php echo e($index === 0 ? 'bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/40' : 'bg-[#2A2A32] border border-pink-500/20'); ?> hover:border-pink-500/40 transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 <?php echo e(($index === 0) ? 'bg-yellow-500' : (($index === 1) ? 'bg-gray-400' : (($index === 2) ? 'bg-orange-600' : 'bg-pink-500'))); ?>">
                            <?php if($index === 0): ?>
                                <i class="fi fi-rr-trophy text-white text-xl"></i>
                            <?php else: ?>
                                <span class="text-white font-bold text-lg"><?php echo e($index + 1); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="text-white font-semibold">
                                <?php echo e(($score->player->team) ? $score->player->team->name : ($score->player->student_name ?? $score->player->user->name ?? 'Unknown')); ?>

                            </div>
                            <div class="text-gray-400 text-sm">
                                <?php if($score->player->team): ?>
                                    Team Members: 
                                    <?php $__currentLoopData = $score->player->team->players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($member->student_name); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    Individual Player
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-pink-400"><?php echo e($score->score); ?></div>
                        <div class="text-gray-400 text-sm">points</div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <div class="mb-4">
                    <i class="fi fi-rr-gamepad text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">No Scores Recorded</h3>
                <p class="text-gray-400">No scores have been recorded for this game yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Game Statistics -->
    <?php if($scores->count() > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-pink-400"><?php echo e($scores->max('score')); ?></div>
                <div class="text-gray-400 text-sm">Highest Score</div>
            </div>
        </div>
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-400"><?php echo e(round($scores->avg('score'), 1)); ?></div>
                <div class="text-gray-400 text-sm">Average Score</div>
            </div>
        </div>
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-400"><?php echo e($scores->count()); ?></div>
                <div class="text-gray-400 text-sm">Total Participants</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mt-8 flex gap-4">
        <a href="<?php echo e(route('classroom.games', $classroom->name)); ?>" 
           class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
            Back to Games
        </a>
        <?php if(auth()->user()->role !== 'student'): ?>
        <a href="<?php echo e(route('games.history', $game)); ?>" 
           class="px-6 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
            View Game History
        </a>
        <?php endif; ?>
    </div>
</div>

<script>
// Add animation for scoreboard
document.addEventListener('DOMContentLoaded', function() {
    const scoreItems = document.querySelectorAll('.space-y-4 > div');
    scoreItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/games/scoreboard.blade.php ENDPATH**/ ?>