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
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-white">Classrooms</h1>
                <button onclick="openCreateClassModal()" 
                        class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white font-medium rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                    <i class="fi fi-rr-plus"></i>
                    Create Classroom
                </button>
            </div>

            <!-- Search Classroom -->
            <div class="mb-6">
                <input type="text" id="search-classroom" placeholder="Find your classroom..." 
                    class="w-full md:w-1/2 rounded-lg p-3 bg-[#2A2A32] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" />
            </div>

            <!-- Classroom Grid -->
            <div id="classroom-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div id="classroom-<?php echo e($classroom->id); ?>" 
                         class="bg-[#211F27] rounded-xl border-2 border-transparent hover:border-pink-500 transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-2"><?php echo e($classroom->name); ?></h3>
                                    <p class="text-gray-400 text-sm line-clamp-2"><?php echo e($classroom->description); ?></p>
                                </div>
                                <div class="bg-pink-500/20 text-pink-500 px-3 py-1 rounded-full text-sm">
                                    <?php echo e($classroom->students->count()); ?> Students
                                </div>
                            </div>
                            
                            <div class="flex items-center text-gray-400">
                                <i class="fi fi-rr-user text-lg mr-2"></i>
                                <span>Teacher: <?php echo e($classroom->teacher->name); ?></span>
                            </div>

                            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-700">
                                <button onclick="openPasswordModal('<?php echo e($classroom->name); ?>')" 
                                        class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                    Open Class
                                </button>
                                <?php if(Auth::id() === $classroom->teacher_id || (Auth::user() && Auth::user()->role === 'admin')): ?>
                                    <button onClick="confirmDelete(<?php echo e($classroom->id); ?>, '<?php echo e($classroom->name); ?>')"
                                            class="text-pink-500 hover:text-pink-400 font-medium transition-colors px-3">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full flex flex-col items-center justify-center py-12 bg-[#211F27] rounded-xl">
                        <div class="text-gray-400 mb-4">
                            <i class="fi fi-rr-classroom text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">No Classrooms Yet</h3>
                        <p class="text-gray-400 text-center max-w-md">
                            Create your first classroom to start managing your students and exercises.
                        </p>
                        <button onclick="openCreateClassModal()" 
                                class="mt-6 px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white font-medium rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                            <i class="fi fi-rr-plus"></i>
                            Create Classroom
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create Classroom Modal -->
    <div id="createClassModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-[#211F27] rounded-xl p-6 w-full max-w-md mx-4">
            <h2 class="text-2xl font-bold text-white mb-6">Create New Classroom</h2>
            <form id="createClassForm" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="class_name" class="block text-gray-400 mb-2">Classroom Name</label>
                    <input type="text" id="class_name" name="class_name" 
                           class="w-full bg-[#2A2A32] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                           required>
                </div>
                <div>
                    <label for="description" class="block text-gray-400 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                            class="w-full bg-[#2A2A32] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            required></textarea>
                </div>
                <div>
                    <label for="password" class="block text-gray-400 mb-2">Password (4-8 characters)</label>
                    <input type="password" id="password" name="password" 
                           class="w-full bg-[#2A2A32] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                           minlength="4" maxlength="8" required>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeCreateClassModal()"
                            class="px-6 py-3 border border-gray-600 text-gray-400 rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Verification Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-[#211F27] rounded-xl p-6 w-full max-w-md mx-4">
            <h2 class="text-2xl font-bold text-white mb-6">Enter Classroom Password</h2>
            <form id="passwordForm" class="space-y-4" method="POST" action="#" onsubmit="return false;">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="className" name="className">
                <div>
                    <label for="classPassword" class="block text-gray-400 mb-2">Password</label>
                    <input type="password" id="classPassword" name="password"
                           class="w-full bg-[#2A2A32] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                           required>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closePasswordModal()"
                            class="px-6 py-3 border border-gray-600 text-gray-400 rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Enter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-[#211F27] rounded-xl p-6 w-full max-w-md mx-4">
            <div class="text-center mb-6">
                <div class="bg-red-500/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fi fi-rr-trash text-red-500 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Delete Classroom</h2>
                <p class="text-gray-400" id="deleteConfirmText"></p>
            </div>
            <div class="flex justify-center space-x-4">
                <button onclick="closeDeleteModal()"
                        class="px-6 py-3 border border-gray-600 text-gray-400 rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button onclick="executeDelete()"
                        class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal for Duplicate Classroom Name -->
    <div id="errorModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-[#211F27] rounded-xl p-6 w-full max-w-sm mx-4 text-center border-2 border-pink-500">
            <div class="flex flex-col items-center mb-4">
                <div class="bg-pink-500/20 w-16 h-16 rounded-full flex items-center justify-center mb-2">
                    <i class="fi fi-rr-exclamation text-pink-500 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Classroom Name Exists</h2>
                <p class="text-gray-400 mb-2" id="errorModalMessage">A classroom with this name already exists. Please choose a different name.</p>
            </div>
            <button onclick="closeErrorModal()" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity mt-2">OK</button>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        let classroomToDelete = null;

        // Create Class Modal Functions
        function openCreateClassModal() {
            document.getElementById('createClassModal').classList.remove('hidden');
            document.getElementById('createClassModal').classList.add('flex');
        }

        function closeCreateClassModal() {
            document.getElementById('createClassModal').classList.add('hidden');
            document.getElementById('createClassModal').classList.remove('flex');
        }

        // Password Modal Functions
        function openPasswordModal(className) {
            document.getElementById('className').value = className;
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('passwordModal').classList.add('flex');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('passwordModal').classList.remove('flex');
        }

        // Delete Modal Functions
        function confirmDelete(id, name) {
            classroomToDelete = id;
            document.getElementById('deleteConfirmText').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            classroomToDelete = null;
        }

        function executeDelete() {
            if (!classroomToDelete) return;
            
            fetch(`/classroom/delete/${classroomToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`classroom-${classroomToDelete}`).remove();
                closeDeleteModal();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete classroom');
            });
        }

        // Create Classroom Form Handler
        document.getElementById('createClassForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('/classrooms', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    class_name: document.getElementById('class_name').value,
                    description: document.getElementById('description').value,
                    password: document.getElementById('password').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    if (data.message && data.message.includes('sudah digunakan')) {
                        showErrorModal('A classroom with this name already exists. Please choose a different name.');
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to create classroom');
            });
        });

        // Password Verification Form Handler
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const className = document.getElementById('className').value;
            const password = document.getElementById('classPassword').value;

            fetch(`/classroom/verify-password/${className}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `/classroom/${className}`;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during password verification.');
            });
        });

        // Search Classroom AJAX
        document.getElementById('search-classroom').addEventListener('input', function(e) {
            const keyword = e.target.value.trim();
            const listContainer = document.getElementById('classroom-list');
            if (keyword.length === 0) {
                location.reload(); // Tampilkan semua classroom jika kosong
                return;
            }
            fetch(`/search-classroom?query=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = `<div class=\"col-span-full flex flex-col items-center justify-center py-12 bg-[#211F27] rounded-xl\">\n                            <div class=\"text-gray-400 mb-4\">\n                                <i class=\"fi fi-rr-classroom text-5xl\"></i>\n                            </div>\n                            <h3 class=\"text-xl font-semibold text-white mb-2\">No Classrooms Found</h3>\n                            <p class=\"text-gray-400 text-center max-w-md\">\n                                No classroom matches your search.\n                            </p>\n                        </div>`;
                    } else {
                        data.forEach(classroom => {
                            html += `\n                            <div class=\"bg-[#211F27] rounded-xl border-2 border-transparent hover:border-pink-500 transition-all duration-200\">\n                                <div class=\"p-6\">\n                                    <div class=\"flex items-start justify-between mb-4\">\n                                        <div>\n                                            <h3 class=\"text-xl font-bold text-white mb-2\">${classroom.name}</h3>\n                                            <p class=\"text-gray-400 text-sm line-clamp-2\">${classroom.description ?? ''}</p>\n                                        </div>\n                                    </div>\n                                    <div class=\"flex items-center justify-between mt-6 pt-4 border-t border-gray-700\">
                                        <button onclick=\"openPasswordModal('${classroom.name}')\" \n                                                class=\"bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity\">\n                                            Open Class\n                                        </button>\n                                    </div>\n                                </div>\n                            </div>`;
                        });
                    }
                    listContainer.innerHTML = html;
                })
                .catch(error => {
                    // Optionally handle error
                });
        });

        // Error Modal Functions
        function showErrorModal(message) {
            document.getElementById('errorModalMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
            document.getElementById('errorModal').classList.add('flex');
        }
        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            document.getElementById('errorModal').classList.remove('flex');
        }
    </script>
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
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/classroom/list.blade.php ENDPATH**/ ?>