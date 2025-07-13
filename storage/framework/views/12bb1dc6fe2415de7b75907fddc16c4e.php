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
    <!-- resources/views/exercises/index.blade.php -->
    <div class="ml-10 p-10">
        <?php if(isset($error)): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <?php echo e($error); ?>

            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white">Exercise List</h1>
                <p class="text-lg text-gray-400 mt-2">Total: <?php echo e($total_exercises); ?> <?php echo e(Str::plural('Exercise', $total_exercises)); ?></p>
            </div>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('exercises.my_results')); ?>" 
                   class="px-4 py-2 border-2 border-pink-500 text-pink-500 rounded-lg hover:opacity-90 transition-opacity flex items-center">
                    <i class="fi fi-rr-list mr-2"></i>
                    My Exercise Results
                </a>
                <?php if(auth()->user()->role !== 'student'): ?>
                    <a href="<?php echo e(route('exercises.create')); ?>" 
                       class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create New Exercise
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4">
                <a href="<?php echo e(route('exercises.index')); ?>" 
                    class="category-tab px-4 py-2 text-white rounded-lg hover:bg-white/5 transition-all <?php echo e($currentCategory === 'all' ? 'bg-gradient-to-r from-pink-500 to-orange-500' : ''); ?>">
                    All Exercises
                </a>
                <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('exercises.index', ['category' => $category])); ?>" 
                        class="category-tab px-4 py-2 text-white rounded-lg hover:bg-white/5 transition-all <?php echo e($currentCategory === $category ? 'bg-gradient-to-r from-pink-500 to-orange-500' : ''); ?>">
                        <?php echo e($category); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(auth()->user()->role !== 'student'): ?>
            <button onclick="window.openAddTopicModal()" 
                    class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center space-x-2">
                <i class="fi fi-rr-plus"></i>
                <span>Add New Topic</span>
            </button>
            <?php endif; ?>
        </div>

        <?php if(auth()->user()->role === 'student' && $noClassroomJoined): ?>
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20 p-8 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-sad-tear text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">Exercises belum tersedia</h3>
                <p class="text-gray-400 mb-4">
                    Silahkan bergabung ke dalam classroom terlebih dahulu untuk melihat exercises.
                </p>
            </div>
        <?php elseif($exercises->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $topicGroups): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="exercise-category" data-category="<?php echo e($category); ?>">
                        <!-- Category Header -->
                        <div class="mb-4">
                                <h2 class="text-2xl font-bold text-white flex items-center">
                                    <i class="fi fi-rr-graduation-cap mr-3 text-pink-500"></i>
                                    <?php echo e($category); ?> Exercises
                                </h2>
                        </div>

                        <!-- Topics List -->
                        <div class="space-y-4">
                                <?php $__currentLoopData = $topicGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topicName => $topicExercises): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-[#211F27] rounded-lg border border-gray-700 hover:border-pink-500/20 transition-all duration-300">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fi fi-rr-book-alt text-pink-500"></i>
                                                    <h3 class="text-xl font-bold text-white"><?php echo e($topicName); ?></h3>
                                                    <span class="text-gray-400">(<?php echo e($topicExercises->count()); ?> <?php echo e(Str::plural('Exercise', $topicExercises->count())); ?>)</span>
                                                </div>
                                                    <button onclick="toggleSubtopics('<?php echo e(str_replace(' ', '_', $topicName)); ?>')" class="text-gray-400 hover:text-white transition-colors">
                                                        <i id="icon-<?php echo e(str_replace(' ', '_', $topicName)); ?>" class="fi fi-rr-angle-small-down transform transition-transform duration-200"></i>
                                                    </button>
                                            </div>
                                        </div>

                                        <div id="subtopics-<?php echo e(str_replace(' ', '_', $topicName)); ?>" class="hidden border-t border-gray-700">
                                            <div class="p-4 space-y-4">
                                                <?php
                                                    $subtopicGroups = $topicExercises->groupBy(function($exercise) {
                                                    return optional($exercise->subtopic)->name ?? 'General';
                                                    });
                                                ?>

                                                <?php $__currentLoopData = $subtopicGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtopicName => $exercises): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="ml-8 p-4 bg-[#1a1a1f] rounded-lg">
                                                        <div class="flex items-center justify-between mb-3">
                                                            <div class="flex items-center space-x-3">
                                                                <i class="fi fi-rr-notebook text-pink-500"></i>
                                                                <h4 class="text-lg font-medium text-white"><?php echo e($subtopicName); ?></h4>
                                                            </div>
                                                            <span class="text-gray-400 text-sm"><?php echo e($exercises->count()); ?> <?php echo e(Str::plural('Exercise', $exercises->count())); ?></span>
                                                        </div>

                                                        <!-- Exercise List -->
                                                        <div class="space-y-3 mt-4">
                                                            <?php $__currentLoopData = $exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="flex items-center justify-between py-2 px-4 bg-[#211F27] rounded-lg hover:bg-[#2a2833] transition-all duration-300">
                                                                    <div class="flex-grow">
                                                                        <div class="flex items-center space-x-3">
                                                                            <h5 class="text-white font-medium"><?php echo e($exercise->title); ?></h5>
                                                                        </div>
                                                                        <p class="text-gray-400 text-sm mt-1"><?php echo e($exercise->description ?: 'No description'); ?></p>
                                                                        <?php if($exercise->classrooms && $exercise->classrooms->count() > 0): ?>
                                                                            <p class="text-xs text-pink-400 mt-1">
                                                                                From <?php echo e($exercise->classrooms->pluck('name')->join(' & ')); ?>

                                                                            </p>
                                                                        <?php endif; ?>
                                                                        <?php if($exercise->creator): ?>
                                                                            <p class="text-gray-500 text-xs mt-1"><i class="fi fi-rr-user mr-1"></i>By <?php echo e($exercise->creator->name); ?></p>
                                                                        <?php endif; ?>
                                                                        <div class="flex items-center space-x-2 mt-2">
                                                                            <span class="text-xs text-gray-500">
                                                                                <i class="fi fi-rr-clock mr-1"></i>
                                                                                <?php echo e($exercise->created_at->diffForHumans()); ?>

                                                                            </span>
                                                                            <?php if($exercise->questions_count): ?>
                                                                                <span class="text-xs text-gray-500">
                                                                                    <i class="fi fi-rr-interrogation mr-1"></i>
                                                                                    <?php echo e($exercise->questions_count); ?> <?php echo e(Str::plural('Question', $exercise->questions_count)); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <button onclick="viewExercise(<?php echo e($exercise->id); ?>)"
                                                                                class="px-3 py-1 text-pink-500 hover:text-white border border-pink-500 rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 transition-all">
                                                                            View
                                                                        </button>
                                                                        <?php if(auth()->user()->role !== 'student'): ?>
                                                                            <button onclick="showSendToClassModal(<?php echo e($exercise->id); ?>)"
                                                                                    class="px-3 py-1 text-pink-500 hover:text-white border border-pink-500 rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 transition-all">
                                                                                <i class="fi fi-rr-paper-plane"></i>
                                                                            </button>
                                                                            <button onclick="editExercise(<?php echo e($exercise->id); ?>)"
                                                                                    class="px-3 py-1 text-blue-500 hover:text-white border border-blue-500 rounded hover:bg-gradient-to-r from-blue-500 to-blue-600 transition-all">
                                                                                Edit
                                                                            </button>
                                                                            <button onclick="deleteExercise(<?php echo e($exercise->id); ?>)"
                                                                                    class="px-3 py-1 text-red-500 hover:text-white border border-red-500 rounded hover:bg-gradient-to-r from-red-500 to-red-600 transition-all">
                                                                                Delete
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20 p-8 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-book-alt text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">No Exercises Found</h3>
                <p class="text-gray-400 mb-4">
                    <?php if(request('category')): ?>
                        No exercises found in the <?php echo e(request('category')); ?> category.
                    <?php else: ?>
                        Start by creating your first exercise.
                    <?php endif; ?>
                </p>
                <a href="<?php echo e(route('exercises.create')); ?>" 
                   class="inline-block px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Create Exercise
                </a>
            </div>
        <?php endif; ?>

        <!-- Exercise Details Modal -->
        <div id="exerciseModal" class="modal hidden fixed inset-0 bg-black bg-opacity-10 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="modal-content bg-[#211F27] rounded-lg shadow-xl border border-pink-500/20 w-full max-w-4xl mx-4 max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white" id="modalTitle"></h2>
                            <p class="text-gray-400 mt-2" id="modalDescription"></p>
                        </div>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                            <i class="fi fi-rr-cross text-xl"></i>
                        </button>
                    </div>
                    <div id="modalContent" class="space-y-6">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Topic Modal -->
        <div id="addTopicModal" class="modal hidden fixed inset-0 bg-black bg-opacity-10 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="modal-content bg-[#211F27] rounded-lg shadow-xl border border-pink-500/20 w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-white">Add New Topic</h2>
                    <button onclick="closeAddTopicModal()" class="text-gray-400 hover:text-white">
                        <i class="fi fi-rr-cross text-xl"></i>
                    </button>
                </div>
                    <form action="<?php echo e(route('topics.store')); ?>" method="POST" class="space-y-4" id="addTopicForm">
                    <?php echo csrf_field(); ?>
                        <!-- Category Selection -->
                        <div>
                            <label for="category" class="block text-lg text-gray-300 font-semibold">Category</label>
                            <select name="category" id="category" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <!-- Topic Name -->
                    <div>
                            <label for="name" class="block text-lg text-gray-300 font-semibold">Topic Name</label>
                            <input type="text" name="name" id="name" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                    </div>

                        <!-- Subtopics Container -->
                    <div>
                            <div class="flex justify-between items-center">
                                <label class="block text-lg text-gray-300 font-semibold">Subtopics</label>
                                <button type="button" onclick="addSubtopicInput()" class="text-pink-500 hover:text-pink-400 flex items-center space-x-1">
                                    <i class="fi fi-rr-plus"></i>
                                    <span>Add Subtopic</span>
                                </button>
                            </div>
                            <div id="subtopicsContainer" class="space-y-3 mt-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="subtopics[]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Enter subtopic name" required>
                                </div>
                            </div>
                    </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeAddTopicModal()" class="px-4 py-2 text-gray-400 hover:text-white">
                            Cancel
                        </button>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                                Add Topic & Subtopics
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Add Subtopic Modal -->
        <div id="addSubtopicModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-[#211F27] rounded-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Add New Subtopic</h2>
                    <button onclick="closeAddSubtopicModal()" class="text-gray-400 hover:text-white">
                        <i class="fi fi-rr-cross text-xl"></i>
                    </button>
                </div>
                <form id="addSubtopicForm" method="POST" action="<?php echo e(route('subtopics.store')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="topic_id" id="subtopicTopicId">
                    <div>
                        <label for="subtopic_name" class="block text-gray-300 text-sm font-medium mb-2">Subtopic Name</label>
                        <input type="text" name="name" id="subtopic_name" placeholder="Enter subtopic name" 
                               class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                    </div>
                    <div>
                        <label for="subtopic_description" class="block text-gray-300 text-sm font-medium mb-2">Description (Optional)</label>
                        <textarea name="description" id="subtopic_description" placeholder="Enter subtopic description" rows="3"
                                  class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddSubtopicModal()" 
                                class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Add Subtopic
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Send to Class Modal -->
        <div id="sendToClassModal" class="modal hidden fixed inset-0 bg-black bg-opacity-10 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-[#211F27] rounded-lg shadow-xl border border-pink-500/20 w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <i class="fi fi-rr-share text-pink-500 text-xl"></i>
                            <h2 class="text-2xl font-bold text-white">Send to Class</h2>
                        </div>
                        <button onclick="closeSendToClassModal()" class="text-gray-400 hover:text-white">
                            <i class="fi fi-rr-cross text-xl"></i>
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Select Classroom</label>
                        <div class="relative">
                            <select id="classroomSelect" 
                                    class="w-full bg-[#1a1a1f] text-white rounded-lg border border-gray-700 p-3 pr-10 focus:border-pink-500 focus:ring-1 focus:ring-pink-500 appearance-none">
                                <option value="">Choose a classroom...</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fi fi-rr-angle-small-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button onclick="closeSendToClassModal()" 
                                class="px-4 py-2 text-white border-2 border-pink-500 rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all duration-300">
                            Cancel
                        </button>
                        <button onclick="sendToClass()" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity duration-300">
                            Send Exercise
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Exercise Confirmation Modal -->
        <div id="deleteExerciseModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-[#211F27] rounded-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <i class="fi fi-rr-trash text-4xl text-pink-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-4 text-center">Delete Exercise?</h2>
                <p class="text-gray-400 mb-6 text-center">Are you sure you want to delete this exercise? This action cannot be undone.</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="confirmDelete()" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Delete
                    </button>
                    <button onclick="closeDeleteModal()" class="px-6 py-3 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function filterExercises(category) {
            const url = new URL(window.location.href);
            if (category === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', category);
            }
            window.location.href = url.toString();
        }

        function toggleSubtopics(topicId) {
            const subtopicsContainer = document.getElementById(`subtopics-${topicId}`);
            const icon = document.getElementById(`icon-${topicId}`);
            
            if (subtopicsContainer.classList.contains('hidden')) {
                subtopicsContainer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                subtopicsContainer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function viewExercise(exerciseId) {
            window.location.href = `/exercises/${exerciseId}/take`;
        }

        function closeModal() {
            document.getElementById('exerciseModal').classList.add('hidden');
            document.getElementById('sendToClassModal').classList.add('hidden');
            document.getElementById('sendToClassModal').classList.remove('flex');
        }

        function editExercise(id) {
            window.location.href = `/exercises/${id}/edit`;
        }

        let exerciseToDelete = null;

        function deleteExercise(id) {
            exerciseToDelete = id;
            document.getElementById('deleteExerciseModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteExerciseModal').classList.add('hidden');
            exerciseToDelete = null;
        }

        function confirmDelete() {
            if (!exerciseToDelete) return;

            fetch(`/exercises/${exerciseToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
            })
            .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Exercise deleted successfully',
                        icon: 'success',
                        background: '#211F27',
                        color: '#fff',
                        confirmButtonColor: '#FF1493'
                    }).then(() => {
                          window.location.reload();
                    });
                      } else {
                    throw new Error(data.message || 'Failed to delete exercise');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message,
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            })
            .finally(() => {
                closeDeleteModal();
            });
        }

        // Close modal when clicking outside
        document.getElementById('exerciseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function generateFileContent(exercise) {
            return `
                <div class="border border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fi fi-rr-document text-2xl text-pink-500 mr-3"></i>
                            <div>
                                <h3 class="text-white font-semibold">Exercise File</h3>
                                <p class="text-gray-400 text-sm">Click to download or view the exercise</p>
                            </div>
                        </div>
                        <a href="/storage/${exercise.file_path}" target="_blank" 
                           class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            View File
                        </a>
                    </div>
                </div>
            `;
        }

        function generateQuestionsContent(questions) {
            return questions.map((q, index) => `
                <div class="border border-gray-700 rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        <div class="bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                            ${index + 1}
                        </div>
                        <div class="flex-grow">
                            <p class="text-white font-semibold mb-4">${q.question}</p>
                            ${q.image_path ? `
                                <div class="mb-4">
                                    <img src="/storage/${q.image_path}" alt="Question Image" class="rounded-lg max-h-48 object-contain">
                                </div>
                            ` : ''}
                            ${q.audio_path ? `
                                <div class="mb-4">
                                    <audio controls class="w-full">
                                        <source src="/storage/${q.audio_path}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            ` : ''}
                            <div class="grid grid-cols-2 gap-3">
                                ${JSON.parse(q.options).map((option, optIndex) => `
                                    <div class="flex items-center space-x-2 ${optIndex === parseInt(q.correct_answer) ? 'text-green-500' : 'text-gray-400'}">
                                        <span class="w-6 h-6 rounded-full border ${optIndex === parseInt(q.correct_answer) ? 'border-green-500' : 'border-gray-500'} flex items-center justify-center">
                                            ${String.fromCharCode(65 + optIndex)}
                                        </span>
                                        <span>${option}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function openAddTopicModal(category = '') {
            const categorySelect = document.getElementById('category');
            if (category) {
                categorySelect.value = category;
            }
            document.getElementById('addTopicModal').classList.remove('hidden');
            document.getElementById('addTopicModal').classList.add('flex');
        }

        function closeAddTopicModal() {
            document.getElementById('addTopicModal').classList.add('hidden');
            document.getElementById('addTopicModal').classList.remove('flex');
        }

        function openAddSubtopicModal(category, topicName) {
            // Get the topic ID based on the topic name
            fetch(`/api/topics/get-id/${encodeURIComponent(topicName)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        document.getElementById('subtopicTopicId').value = data.id;
                        document.getElementById('addSubtopicModal').classList.remove('hidden');
                        document.getElementById('addSubtopicModal').classList.add('flex');
                    } else {
                        console.error('Topic not found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching topic ID:', error);
                });
        }

        function closeAddSubtopicModal() {
            document.getElementById('addSubtopicModal').classList.add('hidden');
            document.getElementById('addSubtopicModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                closeAddTopicModal();
                closeAddSubtopicModal();
            }
        });

        // Handle form submissions with SweetAlert2
        document.getElementById('addTopicForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            // Get all subtopic inputs and add them to formData
            const subtopicInputs = document.querySelectorAll('input[name="subtopics[]"]');
            formData.delete('subtopics[]'); // Remove the original entries
            subtopicInputs.forEach(input => {
                if (input.value.trim()) {
                    formData.append('subtopics[]', input.value.trim());
                }
            });

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        background: '#211F27',
                        color: '#fff',
                        confirmButtonColor: '#FF1493'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to add topic and subtopics');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message,
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            });
        });

        document.getElementById('addSubtopicForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        background: '#211F27',
                        color: '#fff',
                        confirmButtonColor: '#FF1493'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to add subtopic');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message,
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            });
        });

        let selectedExerciseId = null;

        async function loadClassrooms() {
            try {
                const response = await fetch('/get-classrooms', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch classrooms');
                }
                
                const classrooms = await response.json();
                const select = document.getElementById('classroomSelect');
                select.innerHTML = '<option value="">Choose a classroom...</option>';
                
                if (classrooms.length === 0) {
                    select.innerHTML += `<option value="" disabled>No classrooms available</option>`;
                    return;
                }

                classrooms.forEach(classroom => {
                    select.innerHTML += `
                        <option value="${classroom.id}" class="py-2">
                            ${classroom.name}
                        </option>`;
                });
            } catch (error) {
                console.error('Error loading classrooms:', error);
                const select = document.getElementById('classroomSelect');
                select.innerHTML = '<option value="">Error loading classrooms</option>';
                
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load classrooms. Please try again.',
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            }
        }

        async function sendToClass() {
            const classroomId = document.getElementById('classroomSelect').value;
            if (!classroomId) {
                Swal.fire({
                    title: 'Select a Classroom',
                    text: 'Please choose a classroom to send the exercise to',
                    icon: 'warning',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
                return;
            }

            try {
                const response = await fetch(`/exercises/${selectedExerciseId}/send-to-class`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ classroom_id: classroomId })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to send exercise');
                }

                Swal.fire({
                    title: 'Success!',
                    text: result.message,
                    icon: 'success',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
                
                closeSendToClassModal();
            } catch (error) {
                console.error('Error sending exercise:', error);
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Failed to send exercise to class. Please try again.',
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            }
        }

        function showSendToClassModal(exerciseId) {
            selectedExerciseId = exerciseId;
            loadClassrooms();
            document.getElementById('sendToClassModal').classList.remove('hidden');
        }

        function closeSendToClassModal() {
            document.getElementById('sendToClassModal').classList.add('hidden');
            selectedExerciseId = null;
        }

        // Close modal when clicking outside
        document.getElementById('sendToClassModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSendToClassModal();
            }
        });

        function addSubtopicInput() {
            const container = document.getElementById('subtopicsContainer');
            const newInput = document.createElement('div');
            newInput.className = 'flex items-center space-x-2';
            newInput.innerHTML = `
                <input type="text" name="subtopics[]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Enter subtopic name" required>
                <button type="button" onclick="removeSubtopicInput(this)" class="text-red-500 hover:text-red-400">
                    <i class="fi fi-rr-cross"></i>
                </button>
            `;
            container.appendChild(newInput);
        }

        function removeSubtopicInput(button) {
            button.parentElement.remove();
        }
    </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('head'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $attributes = $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $component = $__componentOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?><?php /**PATH D:\Englicious\Englicious\resources\views/exercises/index.blade.php ENDPATH**/ ?>