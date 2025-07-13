<!-- Edit Student Modal -->
<div id="editStudentModal" class="hidden fixed inset-0 bg-opacity-50 z-50">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 w-full max-w-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-white mb-4">Edit Student</h2>
                <form id="editStudentForm" onsubmit="submitEditStudent(event)">
                    <input type="hidden" id="editStudentId" name="student_id">
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Student Name</label>
                        <input type="text" id="editStudentName" name="name" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditStudentModal()" 
                                class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <?php /**PATH D:\Englicious\Englicious\resources\views/components/modals/edit-student-modal.blade.php ENDPATH**/ ?>