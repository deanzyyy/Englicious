<!-- Add Student Modal -->
<div id="addStudentModal" class="fixed inset-0 bg-opacity-50 z-[100] hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 w-full max-w-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-white mb-4">Add New Student</h2>
                <form id="addStudentForm" onsubmit="submitAddStudent(event)">
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Student Name</label>
                        <input type="text" name="name" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddStudentModal()" 
                                class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-notification" class="hidden fixed top-4 right-4 bg-pink-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-[60]">
    <div class="flex items-center space-x-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span id="toast-message"></span>
    </div>
</div> <?php /**PATH D:\Englicious\Englicious\resources\views/components/modals/add-student-modal.blade.php ENDPATH**/ ?>