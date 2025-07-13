<?php $__env->startSection('content'); ?>
<div class="ml-64 p-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2"><?php echo e($game->name); ?> - Game History</h1>
        <p class="text-gray-400 text-lg mt-2">Detailed results and statistics</p>
    </div>

    <!-- Game Info -->
    <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <div class="text-gray-400 text-sm">Classroom</div>
                <div class="text-white font-semibold"><?php echo e($game->classroom->name); ?></div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Exercise</div>
                <div class="text-white font-semibold"><?php echo e($game->exercise->title); ?></div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Mode</div>
                <div class="text-white font-semibold"><?php echo e(ucfirst($game->mode)); ?></div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Type</div>
                <div class="text-white font-semibold"><?php echo e(ucfirst($game->type)); ?></div>
            </div>
        </div>
    </div>

    <!-- Final Results -->
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 mb-6">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Final Results</h2>
            
            <?php if($game->scores->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $game->scores->sortByDesc('score'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <p class="text-gray-400">No scores have been recorded for this game.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Game Statistics -->
    <?php if($game->scores->count() > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-pink-400"><?php echo e($game->scores->max('score')); ?></div>
                <div class="text-gray-400 text-sm">Highest Score</div>
            </div>
        </div>
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-400"><?php echo e(round($game->scores->avg('score'), 1)); ?></div>
                <div class="text-gray-400 text-sm">Average Score</div>
            </div>
        </div>
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-400"><?php echo e($game->scores->count()); ?></div>
                <div class="text-gray-400 text-sm">Total Participants</div>
            </div>
        </div>
        <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-400"><?php echo e($game->exercise->questions->count()); ?></div>
                <div class="text-gray-400 text-sm">Total Questions</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Game Timeline -->
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 mb-6">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Game Timeline</h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-4">
                        <i class="fi fi-rr-check text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-white font-semibold">Game Created</div>
                        <div class="text-gray-400 text-sm"><?php echo e($game->created_at->format('d M Y, H:i')); ?></div>
                    </div>
                </div>
                <?php if($game->status !== 'draft'): ?>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-4">
                        <i class="fi fi-rr-play text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-white font-semibold">Game Started</div>
                        <div class="text-gray-400 text-sm"><?php echo e($game->updated_at->format('d M Y, H:i')); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($game->status === 'finished'): ?>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center mr-4">
                        <i class="fi fi-rr-trophy text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-white font-semibold">Game Completed</div>
                        <div class="text-gray-400 text-sm"><?php echo e($game->updated_at->format('d M Y, H:i')); ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4">
        <a href="<?php echo e(route('games.index')); ?>" 
           class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
            Back to Games
        </a>
        <?php if($game->status === 'draft'): ?>
        <a href="<?php echo e(route('classroom.games', $game->classroom->name)); ?>" 
           class="px-6 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
            Play Game
        </a>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/games/history.blade.php ENDPATH**/ ?>