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
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <!-- Add SweetAlert2 CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="p-10 flex justify-between items-center">
            <div>
                <h1 class="text-white text-4xl font-bold">Edit Exercise</h1>
                <p class="text-lg text-gray-400 mt-2">Edit your exercise here</p>
            </div>
            <button id="backButton" onclick="handleBack()" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-all duration-200 flex items-center space-x-2">
                <i class="fi fi-rs-angle-left"></i>
                <span>Back to Exercise List</span>
            </button>
        </div>

        <div class="container p-2">
            <div class="content bg-[#211F27] p-10 rounded-lg">
                <form id="exercise-form" method="POST" action="<?php echo e(route('exercises.update', $exercise->id)); ?>" class="space-y-6 max-w-4xl" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Common Fields -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="title" class="text-gray-300 text-lg font-semibold">Title Exercise</label>
                            <input type="text" name="title" value="<?php echo e($exercise->title); ?>" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                        </div>

                        <div class="col-span-2">
                            <label for="description" class="text-gray-300 text-lg font-semibold">Description</label>
                            <textarea name="description" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" rows="3"><?php echo e($exercise->description); ?></textarea>
                        </div>

                        <!-- Category Selection -->
                        <div>
                            <label for="category" class="block text-lg text-gray-300 font-semibold">Category</label>
                            <select name="category" id="category" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                                <option value="">Select Category</option>
                                <option value="General" <?php echo e($exercise->topic->category === 'General' ? 'selected' : ''); ?>>General English</option>
                                <option value="TOEFL" <?php echo e($exercise->topic->category === 'TOEFL' ? 'selected' : ''); ?>>TOEFL Preparation</option>
                                <option value="IELTS" <?php echo e($exercise->topic->category === 'IELTS' ? 'selected' : ''); ?>>IELTS Preparation</option>
                            </select>
                        </div>

                        <!-- Topic Selection -->
                        <div>
                            <label for="topic_id" class="block text-lg text-gray-300 font-semibold">Topic</label>
                            <select name="topic_id" id="topic_id" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                                <option value="">Select Topic</option>
                                <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryTopics): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup label="<?php echo e($category); ?>">
                                        <?php $__currentLoopData = $categoryTopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($topic->id); ?>" <?php echo e($exercise->topic_id == $topic->id ? 'selected' : ''); ?>>
                                                <?php echo e($topic->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Subtopic Selection -->
                        <div>
                            <label for="subtopic_id" class="block text-lg text-gray-300 font-semibold">Subtopic</label>
                            <select name="subtopic_id" id="subtopic_id" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                                <option value="">Select Topic First</option>
                                <?php $__currentLoopData = $subtopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtopic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subtopic->id); ?>" <?php echo e($exercise->subtopic_id == $subtopic->id ? 'selected' : ''); ?>>
                                        <?php echo e($subtopic->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div id="questions-container" class="space-y-6">
                        <?php $__currentLoopData = $exercise->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="question-box border border-gray-600 p-6 rounded-lg space-y-4 bg-[#101014]/50">
                                <input type="hidden" name="questions[<?php echo e($index); ?>][id]" value="<?php echo e($question->id); ?>">
                                <div>
                                    <label class="text-gray-300 text-lg font-semibold">Question:</label>
                                    <textarea name="questions[<?php echo e($index); ?>][question_text]" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" rows="2" required><?php echo e($question->question_text); ?></textarea>
                                </div>

                                <!-- Media Support -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Image Upload -->
                                    <div class="border border-gray-600 rounded-lg p-4">
                                        <label class="text-gray-300 text-lg font-semibold block mb-2">Support Image (Optional)</label>
                                        <?php if($question->image_path): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo e(asset('storage/' . $question->image_path)); ?>" alt="Current Image" class="max-w-[300px] max-h-[200px] object-contain rounded">
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex items-center space-x-2">
                                            <input type="file" name="questions[<?php echo e($index); ?>][image]" accept="image/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, GIF (Max. 2MB)</p>
                                    </div>

                                    <!-- Audio Upload -->
                                    <div class="border border-gray-600 rounded-lg p-4">
                                        <label class="text-gray-300 text-lg font-semibold block mb-2">Support Audio (Optional)</label>
                                        <?php if($question->audio_path): ?>
                                            <div class="mb-2">
                                                <audio controls class="w-full">
                                                    <source src="<?php echo e(asset('storage/' . $question->audio_path)); ?>" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex items-center space-x-2">
                                            <input type="file" name="questions[<?php echo e($index); ?>][audio]" accept="audio/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Supported: MP3, WAV (Max. 5MB)</p>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="space-y-3">
                                    <label class="text-gray-300 text-lg font-semibold">Options:</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionIndex => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center space-x-3">
                                                <input type="radio" 
                                                       name="questions[<?php echo e($index); ?>][correct_answer]" 
                                                       value="<?php echo e($optionIndex); ?>" 
                                                       <?php echo e($optionIndex == $question->correct_answer ? 'checked' : ''); ?>

                                                       class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                                <input type="text" 
                                                       name="questions[<?php echo e($index); ?>][options][]" 
                                                       value="<?php echo e($option); ?>"
                                                       class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" 
                                                       required>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="button" onclick="removeQuestion(this)" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-all duration-200 flex items-center space-x-2">
                                        <i class="fi fi-rs-trash"></i>
                                        <span>Remove Question</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <button type="button" onclick="addQuestion()" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:from-pink-600 hover:to-orange-600 transition-all duration-200">
                            Add New Question
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let questionIndex = <?php echo e(count($exercise->questions)); ?>;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function handleBack() {
            window.location.href = '<?php echo e(route("exercises.index")); ?>';
        }

        function removeQuestion(button) {
            console.log('Remove question clicked'); // Debug log
            
            const questionBoxes = document.querySelectorAll('.question-box');
            
            if (questionBoxes.length <= 1) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'You must have at least one question!',
                    icon: 'warning',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
                return;
            }

            const questionBox = button.closest('.question-box');
            const questionId = questionBox.querySelector('input[type="hidden"][name$="[id]"]')?.value;

            // Debug logs
            console.log('Question box found:', questionBox);
            console.log('Question ID:', questionId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove this question?',
                icon: 'warning',
                showCancelButton: true,
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                console.log('SweetAlert result:', result); // Debug log
                
                if (result.isConfirmed) {
                    // If question has an ID, add it to deleted questions
                    if (questionId) {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'deleted_questions[]';
                        input.value = questionId;
                        document.getElementById('exercise-form').appendChild(input);
                        console.log('Added to deleted questions:', questionId); // Debug log
                    }

                    // Remove the question box
                    questionBox.remove();
                    console.log('Question box removed'); // Debug log

                    // Reindex remaining questions
                    document.querySelectorAll('.question-box').forEach((box, index) => {
                        box.querySelectorAll('[name*="questions["]').forEach(element => {
                            let name = element.getAttribute('name');
                            // Replace the index in the name
                            let newName = name.replace(/questions\[\d+\]/, `questions[${index}]`);
                            element.setAttribute('name', newName);
                        });
                    });
                    console.log('Questions reindexed'); // Debug log

                    // Show success message
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Question has been removed.',
                        icon: 'success',
                        background: '#211F27',
                        color: '#fff',
                        confirmButtonColor: '#FF1493'
                    });
                }
            }).catch(error => {
                console.error('SweetAlert error:', error); // Debug log
            });
        }

        // Make sure the function is available globally
        window.removeQuestion = removeQuestion;

        function addQuestion() {
            const template = `
                <div class="question-box border border-gray-600 p-6 rounded-lg space-y-4 bg-[#101014]/50">
                    <div>
                        <label class="text-gray-300 text-lg font-semibold">Question:</label>
                        <textarea name="questions[${questionIndex}][question_text]" class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" rows="2" required></textarea>
                    </div>

                    <!-- Media Support -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Image Upload -->
                        <div class="border border-gray-600 rounded-lg p-4">
                            <label class="text-gray-300 text-lg font-semibold block mb-2">Support Image (Optional)</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" name="questions[${questionIndex}][image]" accept="image/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Supported: JPG, PNG, GIF (Max. 2MB)</p>
                        </div>

                        <!-- Audio Upload -->
                        <div class="border border-gray-600 rounded-lg p-4">
                            <label class="text-gray-300 text-lg font-semibold block mb-2">Support Audio (Optional)</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" name="questions[${questionIndex}][audio]" accept="audio/*" class="block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Supported: MP3, WAV (Max. 5MB)</p>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <label class="text-gray-300 text-lg font-semibold">Options:</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="0" required class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                <input type="text" name="questions[${questionIndex}][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option A" required>
                            </div>
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="1" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                <input type="text" name="questions[${questionIndex}][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option B" required>
                            </div>
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="2" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                <input type="text" name="questions[${questionIndex}][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option C" required>
                            </div>
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="3" class="w-4 h-4 text-pink-500 focus:ring-pink-500">
                                <input type="text" name="questions[${questionIndex}][options][]" class="bg-[#101014] p-3 text-white rounded-lg flex-1 border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" placeholder="Option D" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="removeQuestion(this)" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-all duration-200 flex items-center space-x-2">
                            <i class="fi fi-rs-trash"></i>
                            <span>Remove Question</span>
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', template);
            questionIndex++;
        }

        // Update subtopics when topic changes
        document.getElementById('topic_id').addEventListener('change', function() {
            const topicId = this.value;
            const subtopicSelect = document.getElementById('subtopic_id');
            
            if (!topicId) {
                subtopicSelect.innerHTML = '<option value="">Select Topic First</option>';
                return;
            }

            fetch(`/get-subtopics/${topicId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        subtopicSelect.innerHTML = '<option value="">Select Subtopic</option>';
                        data.data.forEach(subtopic => {
                            subtopicSelect.innerHTML += `<option value="${subtopic.id}">${subtopic.name}</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching subtopics:', error);
                    subtopicSelect.innerHTML = '<option value="">Error loading subtopics</option>';
                });
        });

        function deleteMedia(questionId, type) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            Swal.fire({
                title: 'Delete ' + type.charAt(0).toUpperCase() + type.slice(1) + '?',
                text: "This action cannot be undone",
                icon: 'warning',
                background: '#211F27',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/questions/${questionId}/delete-${type}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Find the media container
                            const mediaContainer = document.querySelector(`[data-question-id="${questionId}"]`);
                            if (mediaContainer) {
                                // Clear the specific preview container
                                const previewContainer = type === 'image' 
                                    ? mediaContainer.querySelector('.image-preview')
                                    : mediaContainer.querySelector('.audio-preview');
                                
                                if (previewContainer) {
                                    previewContainer.innerHTML = '';
                                    
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: `${type.charAt(0).toUpperCase() + type.slice(1)} has been deleted.`,
                                        icon: 'success',
                                        background: '#211F27',
                                        color: '#fff',
                                        confirmButtonColor: '#FF1493'
                                    });
                                }
                            }
                        } else {
                            throw new Error(data.message || `Failed to delete ${type}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message,
                            icon: 'error',
                            background: '#211F27',
                            color: '#fff',
                            confirmButtonColor: '#FF1493'
                        });
                    });
                }
            });
        }
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
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/exercises/edit.blade.php ENDPATH**/ ?>