<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Create New Game</h1>
        <p class="text-gray-400">Setup a new educational game for your classroom</p>
    </div>

    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('games.store')); ?>" id="gameForm">
                <?php echo csrf_field(); ?>
                
                <!-- Basic Game Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Name</label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
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
                                <option value="<?php echo e($classroom->id); ?>" <?php echo e(old('classroom_id') == $classroom->id ? 'selected' : ''); ?>>
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
                                       <?php echo e(old('mode') === 'individual' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Individual</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="mode" value="group" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('mode') === 'group' ? 'checked' : ''); ?>>
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
                                       <?php echo e(old('type', 'offline') === 'offline' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Offline</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="type" value="online" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       <?php echo e(old('type') === 'online' ? 'checked' : ''); ?>>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Online</span>
                            </label>
                        </div>
                        <div class="mt-2 text-xs text-gray-400">
                            <div id="offlineInfo" style="display: none;">
                                <strong>Offline Mode:</strong> Students can only view the game. Teachers control scoring.
                            </div>
                            <div id="onlineInfo" style="display: none;">
                                <strong>Online Mode:</strong> Students can participate and answer questions directly.
                            </div>
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
                            <option value="<?php echo e($exercise->id); ?>" <?php echo e(old('exercise_id') == $exercise->id ? 'selected' : ''); ?>>
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

                <!-- Players/Teams Setup -->
                <div class="mb-6">
                    <label class="block text-pink-500 text-sm font-medium mb-2">
                        <span id="setupLabel">Players/Teams</span> Setup
                    </label>
                    
                    <!-- Online Mode - Waiting for participants -->
                    <div id="onlineParticipantsContainer" style="display: none;">
                        <div class="bg-[#2A2A32] rounded-lg p-4 border border-pink-500/20">
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fi fi-rr-users text-pink-500 text-4xl mb-2"></i>
                                </div>
                                <h3 class="text-white text-lg font-semibold mb-2">Waiting for Participants</h3>
                                <p class="text-gray-400 mb-4">Students will join the game when it starts</p>
                                <div class="bg-[#211F27] rounded-lg p-4 border border-pink-500/20">
                                    <div class="text-sm text-gray-400">
                                        <p><strong>Online Mode:</strong> Students can join and participate directly in the game.</p>
                                        <p class="mt-2">Participants will appear here once they join the game.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offline Mode - Manual player setup -->
                    <div id="offlinePlayersContainer">
                    <div id="playersContainer">
                        <div class="player-item bg-[#2A2A32] rounded-lg p-4 mb-4 border border-pink-500/20">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white text-sm font-medium mb-2">
                                        <span id="playerLabel">Player</span> Name
                                    </label>
                                    <input type="text" name="players[0][name]" required
                                           class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                                           placeholder="Enter name...">
                                </div>
                                <div id="membersContainer" style="display: none;">
                                    <label class="block text-white text-sm font-medium mb-2">Team Members</label>
                                    <div class="space-y-2">
                                        <input type="text" name="players[0][members][]"
                                               class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                                               placeholder="Member 1">
                                        <input type="text" name="players[0][members][]"
                                               class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                                               placeholder="Member 2">
                                        <input type="text" name="players[0][members][]"
                                               class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                                               placeholder="Member 3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addPlayer()" 
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Add <span id="addButtonText">Player</span>
                    </button>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Create Game
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

<script>
let playerCount = 1;

function updateLabels() {
    const mode = document.querySelector('input[name="mode"]:checked')?.value;
    const type = document.querySelector('input[name="type"]:checked')?.value;
    const labels = document.querySelectorAll('#playerLabel');
    const addButtonText = document.getElementById('addButtonText');
    const membersContainers = document.querySelectorAll('#membersContainer');
    const setupLabel = document.getElementById('setupLabel');
    const onlineParticipantsContainer = document.getElementById('onlineParticipantsContainer');
    const offlinePlayersContainer = document.getElementById('offlinePlayersContainer');
    
    // Handle online/offline mode switching
    if (type === 'online') {
        // Show online participants container
        onlineParticipantsContainer.style.display = 'block';
        offlinePlayersContainer.style.display = 'none';
        setupLabel.textContent = 'Players Participants';
        
        // Disable player name inputs for online mode
        const playerInputs = document.querySelectorAll('input[name^="players"][name$="[name]"]');
        playerInputs.forEach(input => {
            input.removeAttribute('required');
            input.disabled = true;
        });
    } else {
        // Show offline players container
        onlineParticipantsContainer.style.display = 'none';
        offlinePlayersContainer.style.display = 'block';
        
        // Enable player name inputs for offline mode
        const playerInputs = document.querySelectorAll('input[name^="players"][name$="[name]"]');
        playerInputs.forEach(input => {
            input.setAttribute('required', 'required');
            input.disabled = false;
        });
    
    if (mode === 'group') {
        labels.forEach(label => label.textContent = 'Team');
        addButtonText.textContent = 'Team';
        membersContainers.forEach(container => container.style.display = 'block');
        setupLabel.textContent = 'Teams';
    } else {
        labels.forEach(label => label.textContent = 'Player');
        addButtonText.textContent = 'Player';
        membersContainers.forEach(container => container.style.display = 'none');
        setupLabel.textContent = 'Players';
        }
    }
    
    // Update type info
    const offlineInfo = document.getElementById('offlineInfo');
    const onlineInfo = document.getElementById('onlineInfo');
    
    if (type === 'offline') {
        offlineInfo.style.display = 'block';
        onlineInfo.style.display = 'none';
    } else if (type === 'online') {
        offlineInfo.style.display = 'none';
        onlineInfo.style.display = 'block';
    }
}

function addPlayer() {
    playerCount++;
    const container = document.getElementById('playersContainer');
    const mode = document.querySelector('input[name="mode"]:checked')?.value;
    const type = document.querySelector('input[name="type"]:checked')?.value;
    
    const playerDiv = document.createElement('div');
    playerDiv.className = 'player-item bg-[#2A2A32] rounded-lg p-4 mb-4 border border-pink-500/20';
    playerDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-white text-sm font-medium mb-2">
                    <span class="player-label">${mode === 'group' ? 'Team' : 'Player'}</span> Name
                </label>
                <input type="text" name="players[${playerCount}][name]" ${type === 'offline' ? 'required' : ''}
                       class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                       placeholder="Enter name..." ${type === 'online' ? 'disabled' : ''}>
            </div>
            <div class="members-container" style="display: ${mode === 'group' ? 'block' : 'none'};">
                <label class="block text-white text-sm font-medium mb-2">Team Members</label>
                <div class="space-y-2">
                    <input type="text" name="players[${playerCount}][members][]"
                           class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                           placeholder="Member 1">
                    <input type="text" name="players[${playerCount}][members][]"
                           class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                           placeholder="Member 2">
                    <input type="text" name="players[${playerCount}][members][]"
                           class="w-full bg-[#211F27] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                           placeholder="Member 3">
                </div>
            </div>
        </div>
        <button type="button" onclick="removePlayer(this)" 
                class="mt-2 px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors">
            Remove
        </button>
    `;
    
    container.appendChild(playerDiv);
}

function removePlayer(button) {
    button.parentElement.remove();
}

// Form validation
document.getElementById('gameForm').addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    
    if (type === 'offline') {
        // For offline mode, ensure at least one player is added
        const playerInputs = document.querySelectorAll('input[name^="players"][name$="[name]"]');
        let hasValidPlayer = false;
        
        playerInputs.forEach(input => {
            if (input.value.trim() !== '') {
                hasValidPlayer = true;
            }
        });
        
        if (!hasValidPlayer) {
            e.preventDefault();
            alert('Please add at least one player for offline mode.');
            return false;
        }
    }
});

// Update labels when mode changes
document.querySelectorAll('input[name="mode"]').forEach(radio => {
    radio.addEventListener('change', updateLabels);
});

document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', updateLabels);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateLabels();
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/games/create.blade.php ENDPATH**/ ?>