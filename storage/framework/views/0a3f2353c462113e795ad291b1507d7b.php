<?php $__env->startSection('content'); ?>
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

    <?php if($students->count() > 0): ?>
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
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

<?php $__env->startPush('scripts'); ?>
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
        const classroomName = "<?php echo e($classroom->name); ?>";
        fetch(`/classroom/${encodeURIComponent(classroomName)}/students`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: formData.get('name')
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/presence.blade.php ENDPATH**/ ?>