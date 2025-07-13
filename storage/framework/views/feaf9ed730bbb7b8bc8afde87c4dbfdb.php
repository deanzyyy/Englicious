

<?php $__env->startSection('content'); ?>
<div class="mt-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white">Waiting Room: <?php echo e($game->name); ?></h2>
        <p class="text-gray-400 text-lg mt-2"><?php echo e($game->exercise->title); ?></p>
        <div class="flex gap-2 mt-2">
            <span class="px-2 py-1 text-xs rounded-full bg-pink-500/20 text-pink-400">
                <?php echo e(ucfirst($game->mode)); ?>

            </span>
            <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                <?php echo e(ucfirst($game->type)); ?>

            </span>
        </div>
    </div>

    <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Participants</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $game->players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20">
                    <div class="text-center">
                        <div class="text-white font-medium mb-2"><?php echo e($player->student_name ?? $player->user->name ?? 'Unknown'); ?></div>
                        <div class="text-gray-400 text-sm">Student</div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-4 text-center text-gray-400">No participants yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(auth()->user()->role === 'teacher'): ?>
    <div class="flex justify-center mt-6">
        <button type="button" id="startGameBtn" class="px-8 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity text-lg font-semibold">
            Start the Game
        </button>
    </div>
    <?php else: ?>
    <div class="flex justify-center mt-6">
        <div class="px-8 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg text-lg font-semibold opacity-80 cursor-not-allowed">
            Waiting for teacher to start the game...
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if(auth()->user()->role !== 'teacher'): ?>
<script>
// Polling status game setiap 2 detik
setInterval(function() {
    fetch("<?php echo e(route('games.status', $game)); ?>")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ongoing') {
                window.location.href = "<?php echo e(route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id])); ?>";
            }
        });
}, 2000);
</script>
<?php endif; ?>

<?php if(auth()->user()->role === 'teacher'): ?>
<script>
document.getElementById('startGameBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Starting...';
    fetch("<?php echo e(route('games.startGame', $game)); ?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "<?php echo e(route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id])); ?>";
        } else {
            alert(data.message || 'Failed to start game');
            btn.disabled = false;
            btn.textContent = 'Start the Game';
        }
    })
    .catch(() => {
        alert('Failed to start game');
        btn.disabled = false;
        btn.textContent = 'Start the Game';
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/games/pragame.blade.php ENDPATH**/ ?>