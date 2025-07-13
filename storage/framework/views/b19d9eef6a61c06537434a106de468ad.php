<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h2 class="text-4xl font-semibold text-white mb-6 text-left">User Management</h2>

    <div class="mb-8">
        <h3 class="text-3xl font-semibold text-white mb-4">Students</h3>
        <div class="bg-[#211F27] p-6 rounded-lg shadow-lg overflow-x-auto border border-pink-500">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Registered At
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($student->name); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($student->email); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($student->created_at->format('d M Y H:i')); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm">
                            <button onclick="openDeleteModal(<?php echo e($student->id); ?>, '<?php echo e($student->name); ?>', 'student')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php if($students->isEmpty()): ?>
                <p class="text-gray-400 text-center py-4">No student accounts found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <h3 class="text-3xl font-semibold text-white mb-4">Teachers</h3>
        <div class="bg-[#211F27] p-6 rounded-lg shadow-lg overflow-x-auto border border-pink-500">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Registered At
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-[#2A2833] text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($teacher->name); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($teacher->email); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm text-white">
                            <?php echo e($teacher->created_at->format('d M Y H:i')); ?>

                        </td>
                        <td class="px-5 py-5 border-b border-gray-700 bg-[#211F27] text-sm">
                            <button onclick="openDeleteModal(<?php echo e($teacher->id); ?>, '<?php echo e($teacher->name); ?>', 'teacher')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php if($teachers->isEmpty()): ?>
                <p class="text-gray-400 text-center py-4">No teacher accounts found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="bg-red-500/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fi fi-rr-trash text-red-500 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Delete User</h2>
            <p class="text-gray-400" id="deleteConfirmText"></p>
        </div>
        <form id="deleteUserForm" method="POST" class="flex justify-center space-x-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="button" onclick="closeDeleteModal()" 
                    class="px-6 py-3 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                Cancel
            </button>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Delete
            </button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let userToDelete = null;

    function openDeleteModal(userId, userName, userRole) {
        userToDelete = userId;
        document.getElementById('deleteConfirmText').textContent = `Are you sure you want to delete ${userRole} "${userName}"? This action cannot be undone.`;
        document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;
        document.getElementById('deleteUserModal').classList.remove('hidden');
        document.getElementById('deleteUserModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteUserModal').classList.add('hidden');
        document.getElementById('deleteUserModal').classList.remove('flex');
        userToDelete = null;
    }

    // Close modal when clicking outside
    document.getElementById('deleteUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/admin/users/index.blade.php ENDPATH**/ ?>