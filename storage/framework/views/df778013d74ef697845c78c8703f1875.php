<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Edit Game</h1>
        <p class="text-gray-400">Update game settings and configuration</p>
    </div>

    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('games.update', $game)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <!-- Basic Game Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Name</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $game->name)); ?>" required
                               class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                               placeholder="Enter game name...">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Classroom</label>
                        <select name="classroom_id" required
                                class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                            <option value="">Select Classroom</option>
                            <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($classroom->id); ?>" <?php echo e(old('classroom_id', $game->classroom_id) == $classroom->id ? 'selected' : ''); ?>>
                                    <?php echo e($classroom->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['classroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Game Mode and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Mode</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="mode" value="individual" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('mode', $game->mode) === 'individual' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Individual</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="mode" value="group" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('mode', $game->mode) === 'group' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Group</span>
                            </label>
                        </div>
                        <?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="type" value="offline" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('type', $game->type) === 'offline' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Offline</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="type" value="online" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('type', $game->type) === 'online' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Online</span>
                            </label>
                        </div>
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Exercise Selection -->
                <div class="mb-6">
                    <label class="block text-pink-500 text-sm font-medium mb-2">Select Exercise</label>
                    <select name="exercise_id" required
                            class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                        <option value="">Select Exercise</option>
                        <?php $__currentLoopData = $exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($exercise->id); ?>" <?php echo e(old('exercise_id', $game->exercise_id) == $exercise->id ? 'selected' : ''); ?>>
                                <?php echo e($exercise->title); ?> (<?php echo e($exercise->questions_count); ?> questions)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['exercise_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Current Game Status -->
                <div class="mb-6">
                    <label class="block text-pink-500 text-sm font-medium mb-2">Current Status</label>
                    <div class="p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20">
                        <div class="flex items-center justify-between">
                            <span class="text-white">Status: <?php echo e(ucfirst($game->status)); ?></span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php if($game->status === 'ongoing'): ?> bg-green-500/20 text-green-400
                                <?php elseif($game->status === 'finished'): ?> bg-purple-500/20 text-purple-400
                                <?php else: ?> bg-yellow-500/20 text-yellow-400 <?php endif; ?>">
                                <?php echo e(ucfirst($game->status)); ?>

                            </span>
                        </div>
                        <?php if($game->status === 'draft'): ?>
                            <p class="text-gray-400 text-sm mt-2">This game is ready to be started.</p>
                        <?php elseif($game->status === 'ongoing'): ?>
                            <p class="text-gray-400 text-sm mt-2">This game is currently being played.</p>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm mt-2">This game has been completed.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Update Game
                    </button>
                    <a href="<?php echo e(route('games.index')); ?>" 
                       class="px-6 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/games/edit.blade.php ENDPATH**/ ?>