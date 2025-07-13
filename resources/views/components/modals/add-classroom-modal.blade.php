<!-- Add Classroom Modal -->
<div id="addClassroomModal" class="fixed inset-0 bg-opacity-50 z-[100] hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 w-full max-w-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-white mb-4">Create New Classroom</h2>
                <form id="addClassroomForm" onsubmit="submitAddClassroom(event)">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Classroom Name</label>
                        <input type="text" name="class_name" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="class_name_error"></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Description</label>
                        <textarea name="description" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required></textarea>
                        <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Password</label>
                        <input type="password" name="password" minlength="4" maxlength="8" class="w-full bg-[#2A2A2A] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <div class="text-red-500 text-sm mt-1 hidden" id="password_error"></div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddClassroomModal()" 
                                class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Create Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddClassroomModal() {
        document.getElementById('addClassroomModal').classList.remove('hidden');
        // Reset form and errors
        document.getElementById('addClassroomForm').reset();
        clearErrors();
    }

    function closeAddClassroomModal() {
        document.getElementById('addClassroomModal').classList.add('hidden');
        document.getElementById('addClassroomForm').reset();
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll('.text-red-500').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    function showError(field, message) {
        const errorDiv = document.getElementById(`${field}_error`);
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }
    }

    function submitAddClassroom(event) {
        event.preventDefault();
        clearErrors();

        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitBtn');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
        
        fetch('{{ route("classrooms.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                class_name: formData.get('class_name'),
                description: formData.get('description'),
                password: formData.get('password')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                closeAddClassroomModal();
                location.reload();
            } else {
                if (typeof data.message === 'object') {
                    // Handle validation errors
                    Object.keys(data.message).forEach(field => {
                        showError(field, data.message[field][0]);
                    });
                } else {
                    throw new Error(data.message);
                }
            }
        })
        .catch(error => {
            showToast(error.message, true);
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Classroom';
        });
    }
</script>
@endpush 