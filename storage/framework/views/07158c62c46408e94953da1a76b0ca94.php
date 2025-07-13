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
    
    
    <div class="mx-25">
        <div class="p-10 flex justify-between items-center">
            <div>
                <h1 class="text-white text-4xl font-bold">Exercise Form</h1>
                <p class="text-lg text-gray-400 mt-2">Make a task for student here, Goodluck!</p>
            </div>
            <button id="backButton" onclick="handleBack()" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-all duration-200 flex items-center space-x-2">
                <i class="fi fi-rs-angle-left"></i>
                <span>Back to Exercise List</span>
            </button>
        </div>

        <div class="container p-2">
            <div class="content bg-[#211F27] p-10 rounded-lg">
                <!-- Toggle Switch -->
                <div class="flex items-center mb-6">
                    <label class="text-gray-300 mr-4 text-lg">Choose Exercise Type:</label>
                    <div class="relative inline-block w-10 mr-2 align-middle select-none">
                        <input type="checkbox" name="toggle" id="exerciseToggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                        <label for="exerciseToggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                    <span id="toggleText" class="text-gray-500 text-lg">Manual Input</span>
                </div>

                <form method="POST" action="<?php echo e(route('exercises.store')); ?>" class="space-y-6 max-w-4xl" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <!-- Common Fields -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="title" class="text-gray-300 text-lg font-semibold">Title Exercise</label>
                            <input type="text" name="title" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Exercise Title" required>
                        </div>

                        <div class="col-span-2">
                            <label for="description" class="text-gray-300 text-lg font-semibold">Description</label>
                            <textarea name="description" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Description" rows="3"></textarea>
                        </div>

                        <div>
                            <label for="duration" class="block text-lg text-gray-300 font-semibold">Duration (minutes)</label>
                            <input type="number" name="duration" min="1" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Duration in minutes" required>
                        </div>

                        <!-- Category Selection -->
                        <div>
                            <label for="category" class="block text-lg text-gray-300 font-semibold">Category</label>
                            <select name="category" id="category" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryTopics): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Topic Selection -->
                        <div>
                            <label for="topic_id" class="block text-lg text-gray-300 font-semibold">Topic</label>
                            <select name="topic_id" id="topic_id" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required disabled>
                                <option value="">Select Category First</option>
                            </select>
                        </div>

                        <!-- Subtopic Selection -->
                        <div>
                            <label for="subtopic_id" class="block text-lg text-gray-300 font-semibold">Subtopic</label>
                            <select name="subtopic_id" id="subtopic_id" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required disabled>
                                <option value="">Select Topic First</option>
                            </select>
                        </div>
                    </div>

                    <!-- Manual Input Section -->
                    <div id="manualSection" class="space-y-6">
                        <div id="tasks-container" class="space-y-6"></div>
                        <div class="flex justify-between items-center pt-4 gap-4">
                            <div class="flex gap-4">
                                <button type="button" onclick="addTask()" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:from-pink-600 hover:to-orange-600 transition-all duration-200">
                                    Add Optional Question
                                </button>
                                <button type="button" onclick="addEssayTask()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg hover:from-blue-600 hover:to-cyan-600 transition-all duration-200">
                                    Essay Question
                                </button>
                            </div>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                                Save Exercise
                            </button>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div id="uploadSection" class="hidden space-y-6">
                        <div class="border-2 border-dashed border-gray-600 rounded-lg p-6">
                            <label for="exercise_file" class="block text-lg text-gray-300 font-semibold mb-2">Upload Your Exercise Here (PDF/PowerPoint)</label>
                            <input type="file" 
                                   name="exercise_file" 
                                   id="exercise_file" 
                                   accept=".pdf,.ppt,.pptx" 
                                   class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600" />
                            <p class="text-sm text-gray-400 mt-2">Accepted formats: PDF, PowerPoint (Max. 10MB)</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                                Upload Exercise
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Template for Questions -->
                <template id="task-template">
                    <div class="task-box border border-gray-600 p-6 rounded-lg space-y-4 bg-[#101014]/50">
                        <div>
                            <label class="text-gray-300 text-lg font-semibold">Question:</label>
                            <textarea name="questions[__INDEX__][question_text]" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" rows="2" required></textarea>
                        </div>

                        <!-- Optional Media Support -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Image Upload -->
                            <div class="border border-gray-600 rounded-lg p-4">
                                <label class="text-gray-300 text-lg font-semibold block mb-2">Support Image (Optional)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="file" name="questions[__INDEX__][image]" accept="image/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600" />
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, GIF (Max. 2MB)</p>
                            </div>

                            <!-- Audio Upload -->
                            <div class="border border-gray-600 rounded-lg p-4">
                                <label class="text-gray-300 text-lg font-semibold block mb-2">Support Audio (Optional)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="file" name="questions[__INDEX__][audio]" accept="audio/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600" />
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Supported: MP3, WAV (Max. 5MB)</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-gray-300 text-lg font-semibold">Options:</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="questions[__INDEX__][correct_answer]" value="0" required class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                    <input type="text" name="questions[__INDEX__][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option A" required>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="questions[__INDEX__][correct_answer]" value="1" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                    <input type="text" name="questions[__INDEX__][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option B" required>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="questions[__INDEX__][correct_answer]" value="2" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                    <input type="text" name="questions[__INDEX__][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option C" required>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="questions[__INDEX__][correct_answer]" value="3" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                    <input type="text" name="questions[__INDEX__][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option D" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" class="px-4 py-2 border border-pink-500 text-pink rounded-lg hover:bg-pink-500 hover:text-white hover:border-none transition-all duration-200 remove-task">Remove Question</button>
                        </div>
                    </div>
                </template>

                <!-- Template for Essay Question -->
                <template id="essay-task-template">
                    <div class="task-box border border-blue-600 p-6 rounded-lg space-y-4 bg-[#101014]/50">
                        <div>
                            <label class="text-blue-300 text-lg font-semibold">Essay Question:</label>
                            <textarea name="essay_questions[__INDEX__][question_text]" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-blue-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" rows="2" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="px-4 py-2 border border-blue-500 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white hover:border-none transition-all duration-200 remove-task">Remove Question</button>
                        </div>
                    </div>
                </template>

                <!-- Debug Info (hidden in production) -->
                <div id="debugInfo" class="mt-4 p-4 bg-gray-800 rounded-lg text-white text-sm" style="display: none;">
                    <pre id="debugOutput"></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Confirmation Modal -->
    <div id="backModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-[#211F27] rounded-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <i class="fi fi-rr-exclamation text-4xl text-pink-500"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4 text-center">Leave Page?</h2>
            <p class="text-gray-400 mb-6 text-center">Are you sure you want to leave? All unsaved questions will be lost.</p>
            <div class="flex justify-center space-x-4">
                <button onclick="confirmBack()" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Leave Page
                </button>
                <button onclick="closeBackModal()" class="px-6 py-3 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                    Stay
                </button>
            </div>
        </div>
    </div>

    <?php $__env->startPush('head'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php $__env->stopPush(); ?>

    <style>
        /* Toggle Switch Styles */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #68D391;
        }
        .toggle-label {
            transition: background-color 0.2s ease-in;
        }
    </style>

    <script>
        let taskIndex = 0;
        let hasUnsavedChanges = false;

        function handleBack() {
            const questionsContainer = document.getElementById('tasks-container');
            const hasQuestions = questionsContainer.children.length > 0;
            
            if (hasQuestions) {
                document.getElementById('backModal').classList.remove('hidden');
            } else {
                window.location.href = '<?php echo e(route("exercises.index")); ?>';
            }
        }

        function confirmBack() {
            window.location.href = '<?php echo e(route("exercises.index")); ?>';
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('backModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBackModal();
            }
        });

        function addTask() {
            const template = document.getElementById('task-template').innerHTML;
            const newTask = template.replace(/__INDEX__/g, taskIndex);
            document.getElementById('tasks-container').insertAdjacentHTML('beforeend', newTask);
            taskIndex++;
            hasUnsavedChanges = true;
        }

        function addEssayTask() {
            const template = document.getElementById('essay-task-template').innerHTML;
            const newTask = template.replace(/__INDEX__/g, taskIndex);
            document.getElementById('tasks-container').insertAdjacentHTML('beforeend', newTask);
            taskIndex++;
            hasUnsavedChanges = true;
        }

        // Remove Task
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task')) {
                e.target.closest('.task-box').remove();
            }
        });

        // Toggle between manual and upload
        const toggle = document.getElementById('exerciseToggle');
        const toggleText = document.getElementById('toggleText');
        const manualSection = document.getElementById('manualSection');
        const uploadSection = document.getElementById('uploadSection');

        toggle.addEventListener('change', function() {
            if (this.checked) {
                toggleText.textContent = 'File Upload';
                manualSection.classList.add('hidden');
                uploadSection.classList.remove('hidden');
            } else {
                toggleText.textContent = 'Manual Input';
                manualSection.classList.remove('hidden');
                uploadSection.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const topicSelect = document.getElementById('topic_id');
            const subtopicSelect = document.getElementById('subtopic_id');
            const debugOutput = document.getElementById('debugOutput');
            const debugInfo = document.getElementById('debugInfo');

            // Debug function
            function debug(message) {
                console.log(message);
                debugOutput.textContent += message + '\n';
                debugInfo.style.display = 'block';
            }

            // Clear debug
            function clearDebug() {
                debugOutput.textContent = '';
            }

            // Store all topics and subtopics data
            const topicsData = <?php echo json_encode($topics, 15, 512) ?>;
            const subtopicsData = <?php echo json_encode($subtopics, 15, 512) ?>;

            // Category change handler
            categorySelect.addEventListener('change', function() {
                const category = this.value;
                const topicSelect = document.getElementById('topic_id');
                const subtopicSelect = document.getElementById('subtopic_id');
                
                // Reset and disable dependent dropdowns if no category selected
                if (!category) {
                    topicSelect.innerHTML = '<option value="">Select Category First</option>';
                    subtopicSelect.innerHTML = '<option value="">Select Topic First</option>';
                    topicSelect.disabled = true;
                    subtopicSelect.disabled = true;
                    return;
                }

                // Enable and populate topics dropdown
                topicSelect.disabled = false;
                topicSelect.innerHTML = '<option value="">Select Topic</option>';
                
                if (topicsData[category]) {
                    topicsData[category].forEach(topic => {
                        topicSelect.innerHTML += `<option value="${topic.id}">${topic.name}</option>`;
                    });
                }

                // Reset subtopics
                subtopicSelect.innerHTML = '<option value="">Select Topic First</option>';
                subtopicSelect.disabled = true;
            });

            // Topic change handler
            topicSelect.addEventListener('change', function() {
                const topicId = this.value;
                const subtopicSelect = document.getElementById('subtopic_id');
                
                if (!topicId) {
                    subtopicSelect.innerHTML = '<option value="">Select Topic First</option>';
                    subtopicSelect.disabled = true;
                    return;
                }

                // Enable and populate subtopics dropdown
                subtopicSelect.disabled = false;
                subtopicSelect.innerHTML = '<option value="">Select Subtopic</option>';

                const topicSubtopics = subtopicsData.filter(subtopic => subtopic.topic_id == topicId);
                topicSubtopics.forEach(subtopic => {
                    subtopicSelect.innerHTML += `<option value="${subtopic.id}">${subtopic.name}</option>`;
                });
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $attributes = $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $component = $__componentOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>

</rewritten_file><?php /**PATH D:\Englicious\Englicious\resources\views/exercises/create.blade.php ENDPATH**/ ?>