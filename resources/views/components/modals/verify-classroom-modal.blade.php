<!-- Verify Classroom Password Modal -->
<div id="verifyClassroomModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 w-full max-w-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-white mb-4">Enter Classroom Password</h2>
                <form id="verifyClassroomForm" onsubmit="submitVerifyClassroom(event)">
                    <input type="hidden" id="classroomName" name="classroomName">
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Password</label>
                        <input type="password" name="password" maxlength="8" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeVerifyClassroomModal()" 
                                class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Enter Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openVerifyClassroomModal(classroomName) {
        document.getElementById('classroomName').value = classroomName;
        document.getElementById('verifyClassroomModal').classList.remove('hidden');
    }

    function closeVerifyClassroomModal() {
        document.getElementById('verifyClassroomModal').classList.add('hidden');
        document.getElementById('verifyClassroomForm').reset();
    }

    function submitVerifyClassroom(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const classroomName = formData.get('classroomName');
        
        fetch(`/classroom/verify-password/${encodeURIComponent(classroomName)}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                password: formData.get('password')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                closeVerifyClassroomModal();
                window.location.href = `/classroom/${encodeURIComponent(classroomName)}`;
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            showToast(error.message, true);
        });
    }
</script>
@endpush 