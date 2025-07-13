<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
  </head>
  <body class="bg-[#101014]">
    
    <div class="flex min-h-screen">
        <?php if (isset($component)) { $__componentOriginald31f0a1d6e85408eecaaa9471b609820 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald31f0a1d6e85408eecaaa9471b609820 = $attributes; } ?>
<?php $component = App\View\Components\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $attributes = $__attributesOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $component = $__componentOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__componentOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>

        <div class="ml-64 p-10 w-full">
            <div class="judul flex justify-between">
                <div>
                    <h1 class="text-white text-4xl font-bold"><?php echo e($classroom->name); ?></h1>
                    <?php
                        $desc = '';
                        if (request()->is('classroom/'.$classroom->name.'/materials')) {
                            $desc = 'Explore a variety of learning materials to boost your knowledge!';
                        } elseif (request()->routeIs('classroom.exercises')) {
                            $desc = 'Challenge yourself with interactive exercises and track your progress!';
                        } elseif (request()->is('classroom/'.$classroom->name.'/assignments') || request()->is('classroom/'.$classroom->name.'/assignments/*')) {
                            $desc = 'Complete your assignments and show your best work!';
                        } elseif (request()->is('classroom/'.$classroom->name.'/schedules')) {
                            $desc = 'Stay organized with your class schedule and never miss a session!';
                        } elseif (request()->is('classroom/'.$classroom->name.'/attendance')) {
                            $desc = 'Check and manage your attendance records easily!';
                        } elseif (request()->is('classroom/'.$classroom->name.'/grades')) {
                            $desc = 'Review your grades and celebrate your achievements!';
                        } elseif (request()->is('classroom/'.$classroom->name.'/games*')) {
                            $desc = 'Engage in fun educational games and compete with classmates!';
                        } else {
                            $desc = 'Welcome to your classroom dashboard!';
                        }
                    ?>
                    <p class="text-gray-400 text-lg mt-2"><?php echo e($desc); ?></p>
                </div>
                <?php if(Auth::check() && Auth::user()->role === 'student'): ?>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="w-25 h-10 flex items-center justify-center text-white bg-gradient-to-r from-gray-500 to-gray-700 rounded-lg hover:bg-none hover:border-2 hover:border-white transition-all duration-300">
                        <i class="fi fi-rr-arrow-left text-lg mr-2"></i>Back
                    </a>
                    <form id="leave-classroom-form" action="<?php echo e(route('classroom.leave', $classroom->name)); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                    <button type="button" onclick="confirmLeaveClassroom()" class="w-25 h-10 text-white bg-gradient-to-r from-pink-500 to-orange-500 rounded-lg hover:bg-none hover:border-2 hover:border-white transition-all duration-300">Exit</button>
                </div>
                <?php endif; ?>
            </div>
        
            <div class="mt-10 flex w-full">
                <div class="flex space-x-6">
                    <a href="<?php echo e(route('classroom.materials', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/materials') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Materials</a>
                    <a href="<?php echo e(route('classroom.exercises', $classroom->name)); ?>" 
                        class="<?php echo e(request()->routeIs('classroom.exercises') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Exercise</a>
                    <a href="<?php echo e(route('classroom.assignments.index', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/assignments') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Assignments</a>
                    <a href="<?php echo e(route('classroom.schedules.index', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/schedules') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Schedule</a>
                    <a href="<?php echo e(route('classroom.presence', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/attendance') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Presence</a>
                    <a href="<?php echo e(route('classroom.grades', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/grades') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Grade</a>
                    <a href="<?php echo e(route('classroom.games', $classroom->name)); ?>" 
                        class="<?php echo e(request()->is('classroom/'.$classroom->name.'/games*') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'); ?> relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Games</a>
                </div>
            </div>

            <div class="border-b-2 border-gray-500 pt-5 w-full"></div>
        
            <div>
                <?php if(request()->is('classroom/'.$classroom->name.'/materials')): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(request()->is('classroom/'.$classroom->name.'/exercises')): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(request()->is('classroom/'.$classroom->name.'/assignments') || request()->is('classroom/'.$classroom->name.'/assignments/*')): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(request()->is('classroom/'.$classroom->name.'/grades')): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(request()->is('classroom/'.$classroom->name.'/games*')): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(request()->is('classroom/'.$classroom->name.'/presence')): ?>
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Student Attendance</h2>
                                <p class="text-gray-400 text-lg mt-2">Manage attendance for <?php echo e(Carbon\Carbon::today()->format('l, d F Y')); ?></p>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="openAddStudentModal()" 
                                    class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center cursor-pointer">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Student
                                </button>
                                <a href="<?php echo e(route('attendance.history', $classroom->id)); ?>" 
                                    class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    View History
                                </a>
                                <a href="<?php echo e(route('attendance.export-pdf', $classroom->id)); ?>" 
                                    class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>

                        <?php if($classroom->students->count() > 0): ?>
                        <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20">
                            <div class="p-6">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left border-b border-white/20">
                                            <th class="py-3 px-4 text-gray-400 font-medium">No</th>
                                            <th class="py-3 px-4 text-gray-400 font-medium">Student Name</th>
                                            <th class="py-3 px-4 text-gray-400 font-medium">Status</th>
                                            <th class="py-3 px-4 text-gray-400 font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $classroom->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-b border-white/20 hover:bg-pink-500/5">
                                            <td class="py-4 px-4 text-white"><?php echo e($index + 1); ?></td>
                                            <td class="py-4 px-4 text-white"><?php echo e($student->name); ?></td>
                                            <td class="py-4 px-4">
                                                <div class="flex gap-3">
                                                    <button onclick="updateAttendance(<?php echo e($student->id); ?>, 'present')" 
                                                            class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id]->status === 'present' ? 'bg-green-500' : 'bg-gray-600 hover:bg-green-500'); ?> transition-colors"
                                                            title="Present">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                    <button onclick="updateAttendance(<?php echo e($student->id); ?>, 'late')"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id]->status === 'late' ? 'bg-blue-500' : 'bg-gray-600 hover:bg-blue-500'); ?> transition-colors"
                                                            title="Late">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </button>
                                                    <button onclick="updateAttendance(<?php echo e($student->id); ?>, 'excused')"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id]->status === 'excused' ? 'bg-yellow-500' : 'bg-gray-600 hover:bg-yellow-500'); ?> transition-colors"
                                                            title="Excused">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </button>
                                                    <button onclick="updateAttendance(<?php echo e($student->id); ?>, 'absent')"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id]->status === 'absent' ? 'bg-red-500' : 'bg-gray-600 hover:bg-red-500'); ?> transition-colors"
                                                            title="Absent">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="flex gap-2">
                                                    <button onclick="openEditStudentModal(<?php echo e($student->id); ?>, '<?php echo e($student->name); ?>')" 
                                                            class="px-3 py-1 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90 transition-opacity">
                                                        Edit
                                                    </button>
                                                    <button onclick="deleteStudent(<?php echo e($student->id); ?>)" 
                                                            class="px-3 py-1 border-2 border-pink-500 text-white rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center">
                            <div class="mb-4">
                                <i class="fi fi-rr-users text-pink-500 text-5xl"></i>
                            </div>
                            <h3 class="text-white text-xl font-semibold mb-2">No Students Added Yet</h3>
                            <p class="text-gray-400 mb-4">Start by adding students to this classroom</p>
                            <button type="button" onclick="openAddStudentModal()" 
                                    class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity cursor-pointer">
                                Add First Student
                            </button>
                        </div>
                        <?php endif; ?>

                        <!-- Add Student Modal -->
                        <?php echo $__env->make('components.modals.add-student-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <!-- Edit Student Modal -->
                        <?php echo $__env->make('components.modals.edit-student-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php else: ?>
                    <?php if(Auth::check() && Auth::user()->role === 'student' && request()->is('classroom/'.$classroom->name)): ?>
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-white mb-4" style="margin-top: 24px; margin-bottom: 12px;">Hello, <span class="text-pink-500"><?php echo e(Auth::user()->name); ?></span>.</h2>
                            <p class="text-gray-300 mb-8">Welcome back. Ready to continue your learning journey?</p>
                            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                                <h3 class="text-xl font-semibold text-white mb-2">Latest Exercise</h3>
                                <?php
                                    $todayExercise = $classroom->exercises->where('created_at', '>=', \Carbon\Carbon::today())->first();
                                ?>
                                <?php if($todayExercise): ?>
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <div class="text-lg text-pink-500 font-bold"><?php echo e($todayExercise->title); ?></div>
                                            <div class="text-gray-400 text-sm mb-2">Uploaded at <?php echo e($todayExercise->created_at->format('d M Y H:i')); ?></div>
                                            <div class="text-gray-300"><?php echo e($todayExercise->description); ?></div>
                                        </div>
                                        <div class="mt-4 md:mt-0">
                                            <a href="<?php echo e(route('classroom.exercises', $classroom->name)); ?>" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90 transition">Go to Exercises</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-gray-400">No exercises have been uploaded yet.</div>
                                <?php endif; ?>
                            </div>
                            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                                <h3 class="text-xl font-semibold text-white mb-2">Exercises you haven't done yet</h3>
                                <?php if($incompleteExercises->count() > 0): ?>
                                    <ul class="list-disc pl-6">
                                        <?php $__currentLoopData = $incompleteExercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="mb-1 flex items-center justify-between">
                                                <div>
                                                    <span class="text-pink-500 font-semibold"><?php echo e($exercise->title); ?></span>
                                                    <span class="text-gray-400">- <?php echo e($exercise->description); ?></span>
                                                </div>
                                                <?php if(isset($todayExercise) && $todayExercise && $todayExercise->id === $exercise->id): ?>
                                                    <a href="<?php echo e(route('classroom.exercise.take', ['className' => $classroom->name, 'exerciseId' => $exercise->id])); ?>" class="ml-4 px-4 py-1 bg-none text-pink-500 border-2 border-pink-500 rounded hover:bg-pink-500 hover:text-white transition">Complete now!</a>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    <div class="text-green-400">You have completed all exercises. Great job.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <script>
        function updateAttendance(studentId, status) {
            fetch('<?php echo e(route("attendance.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    classroom_id: <?php echo e($classroom->id); ?>,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Attendance updated successfully');
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                alert(error.message);
                location.reload();
            });
        }

        // Student management functions
        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
            document.getElementById('addStudentForm').reset();
        }

        function submitAddStudent(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            fetch('<?php echo e(route("students.store")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    classroom_id: <?php echo e($classroom->id); ?>

                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    closeAddStudentModal();
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showToast(error.message, true);
            });
        }

        function openEditStudentModal(studentId, studentName) {
            document.getElementById('editStudentId').value = studentId;
            document.getElementById('editStudentName').value = studentName;
            document.getElementById('editStudentModal').classList.remove('hidden');
        }

        function closeEditStudentModal() {
            document.getElementById('editStudentModal').classList.add('hidden');
        }

        function submitEditStudent(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            fetch(`/students/${formData.get('student_id')}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    name: formData.get('name')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Student updated successfully');
                    closeEditStudentModal();
                    location.reload();
                }
            });
        }

        function deleteStudent(studentId) {
            if (confirm('Are you sure you want to delete this student?')) {
                fetch(`/students/${studentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        location.reload();
                    }
                });
            }
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('animate-slide-in');
            
            if (isError) {
                toast.classList.add('bg-red-500');
                toast.classList.remove('bg-pink-500');
            } else {
                toast.classList.add('bg-pink-500');
                toast.classList.remove('bg-red-500');
            }

            setTimeout(() => {
                toast.classList.add('animate-fade-out');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('animate-slide-in', 'animate-fade-out');
                }, 300);
            }, 3000);
        }

        function confirmLeaveClassroom() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be removed from this classroom!",
                icon: 'warning',
                background: '#211F27',
                color: '#FFFFFF',
                showCancelButton: true,
                confirmButtonColor: '#EC4899',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Yes, leave it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('leave-classroom-form').submit();
                }
            });
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>
</html> <?php /**PATH D:\Englicious\Englicious\resources\views/classroom/show.blade.php ENDPATH**/ ?>