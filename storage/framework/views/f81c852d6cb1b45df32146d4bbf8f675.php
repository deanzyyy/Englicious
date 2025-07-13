<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-[#211F27] rounded-lg p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-pink-500"><?php echo e($classroom->name); ?> - Student Grades</h2>
            <a href="<?php echo e(route('classroom.show', $classroom->name)); ?>" 
               class="text-gray-400 hover:text-pink-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
        <?php if(count($studentGrades) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-[#2A2833] rounded-lg">
                    <thead>
                        <tr class="bg-[#1a1a1f]">
                            <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                Exercise Avg
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                Assignment Avg
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                Final Score
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php $__currentLoopData = $studentGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-[#32323A] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-pink-500 to-orange-500 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    <?php echo e(strtoupper(substr($gradeData['student']->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                <?php echo e($gradeData['student']->name); ?>

                                            </div>
                                            <div class="text-sm text-gray-400">
                                                <?php echo e($gradeData['student']->email); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-medium">
                                        <?php echo e(number_format($gradeData['avgExercise'], 2)); ?>%
                                    </span>
                                    <div class="text-xs text-gray-400">
                                        <?php echo e($gradeData['exerciseSubmissions']->count()); ?> exercises
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if($gradeData['avgAssignment'] !== null): ?>
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-medium">
                                            <?php echo e(number_format($gradeData['avgAssignment'], 2)); ?>%
                                        </span>
                                        <div class="text-xs text-gray-400">
                                            <?php echo e($gradeData['assignmentSubmissions']->whereNotNull('grade')->count()); ?> graded
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-500">Menunggu penilaian</span>
                                        <div class="text-xs text-gray-400">
                                            <?php echo e($gradeData['assignmentSubmissions']->count()); ?> submitted
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if($gradeData['finalScore'] !== null): ?>
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-bold text-lg">
                                            <?php echo e(number_format($gradeData['finalScore'], 2)); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-500">--</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick="viewStudentDetails('<?php echo e($gradeData['student']->id); ?>', '<?php echo e($gradeData['student']->name); ?>')"
                                            class="text-pink-500 hover:text-pink-400 font-medium transition-colors">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <!-- Student Details Modal -->
            <div id="student-details-modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-[#211F27] p-6 rounded-lg shadow-lg w-full max-w-4xl max-h-[80vh] overflow-y-auto border border-pink-500">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-white" id="modal-student-name"></h3>
                        <button onclick="closeStudentDetailsModal()" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div id="student-details-content">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
            <!-- Grade Assignment Modal -->
            <div id="grade-assignment-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-[#211F27] p-6 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
                    <h3 class="text-xl font-semibold text-white mb-4">Grade Assignment</h3>
                    <form id="grade-assignment-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="grade-submission-id" name="submission_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Assignment</label>
                            <p id="grade-assignment-title" class="text-white font-medium"></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Student</label>
                            <p id="grade-student-name" class="text-white font-medium"></p>
                        </div>
                        <div class="mb-4">
                            <label for="grade-score" class="block text-sm font-medium text-gray-300 mb-2">Grade (%)</label>
                            <input type="number" id="grade-score" name="grade" min="0" max="100" step="0.01" required
                                   class="w-full p-2 rounded border border-gray-700 text-white bg-[#2A2833] focus:border-pink-500 focus:outline-none">
                        </div>
                        <div class="mb-6">
                            <label for="grade-feedback" class="block text-sm font-medium text-gray-300 mb-2">Feedback (Optional)</label>
                            <textarea id="grade-feedback" name="feedback" rows="3"
                                      class="w-full p-2 rounded border border-gray-700 text-white bg-[#2A2833] focus:border-pink-500 focus:outline-none"></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeGradeModal()" 
                                    class="px-4 py-2 border-2 border-pink-500 text-pink-500 hover:bg-red-500 hover:text-white hover:border-0 rounded transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition-colors">
                                Save Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fi fi-rr-users text-gray-500 text-4xl mb-4"></i>
                <p class="text-gray-400">No students in this classroom yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function viewStudentDetails(studentId, studentName) {
        document.getElementById('modal-student-name').textContent = studentName;
        document.getElementById('student-details-modal').classList.remove('hidden');
        
        // Load student details via AJAX
        fetch(`/classroom/<?php echo e($classroom->name); ?>/grades/student/${studentId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('student-details-content').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error loading student details:', error);
                document.getElementById('student-details-content').innerHTML = '<p class="text-red-500">Error loading student details</p>';
            });
    }

    function closeStudentDetailsModal() {
        document.getElementById('student-details-modal').classList.add('hidden');
    }

    function openGradeModal(submissionId, assignmentTitle, studentName) {
        document.getElementById('grade-submission-id').value = submissionId;
        document.getElementById('grade-assignment-title').textContent = assignmentTitle;
        document.getElementById('grade-student-name').textContent = studentName;
        document.getElementById('grade-assignment-modal').classList.remove('hidden');
    }

    function closeGradeModal() {
        document.getElementById('grade-assignment-modal').classList.add('hidden');
        document.getElementById('grade-assignment-form').reset();
    }

    // Handle grade form submission
    document.getElementById('grade-assignment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submissionId = formData.get('submission_id');
        
        fetch(`/classroom/<?php echo e($classroom->name); ?>/grades/assignment/${submissionId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                grade: formData.get('grade'),
                feedback: formData.get('feedback')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeGradeModal();
                // Reload the page to show updated grades
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the grade');
        });
    });
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/grades/teacher.blade.php ENDPATH**/ ?>