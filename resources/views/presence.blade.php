<x-layout>
    <div class="ml-10 p-10 relative">
        {{-- Toast Notification --}}
        <div id="toast-notification" class="hidden fixed top-4 right-4 bg-pink-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-[60]">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="toast-message"></span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-8 relative z-10">
            <div>
                <h1 class="text-4xl font-bold text-white">{{ $classroom->name }} - Student Attendance</h1>
                <p class="text-gray-400 text-lg mt-2">Manage your student attendance for {{ Carbon\Carbon::today()->format('l, d F Y') }}</p>
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="openAddStudentModal()" 
                    class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center cursor-pointer relative z-10">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Student
                </button>
                <a href="{{ route('attendance.history', $classroom->id) }}" 
                    class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    View History
                </a>
                <a href="{{ route('attendance.export-pdf', $classroom->id) }}" 
                    class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        @if($students->count() > 0)
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
                        @foreach($students as $index => $student)
                        <tr class="border-b border-white/20 hover:bg-pink-500/5">
                            <td class="py-4 px-4 text-white">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 text-white">{{ $student->name }}</td>
                            <td class="py-4 px-4">
                                <div class="flex gap-3">
                                    <button onclick="updateAttendance({{ $student->id }}, 'present')" 
                                            class="w-8 h-8 rounded-full flex items-center justify-center {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'present' ? 'bg-green-500' : 'bg-gray-600 hover:bg-green-500' }} transition-colors"
                                            title="Present">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button onclick="updateAttendance({{ $student->id }}, 'late')"
                                            class="w-8 h-8 rounded-full flex items-center justify-center {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'late' ? 'bg-blue-500' : 'bg-gray-600 hover:bg-blue-500' }} transition-colors"
                                            title="Late">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <button onclick="updateAttendance({{ $student->id }}, 'excused')"
                                            class="w-8 h-8 rounded-full flex items-center justify-center {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'excused' ? 'bg-yellow-500' : 'bg-gray-600 hover:bg-yellow-500' }} transition-colors"
                                            title="Excused">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <button onclick="updateAttendance({{ $student->id }}, 'absent')"
                                            class="w-8 h-8 rounded-full flex items-center justify-center {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'absent' ? 'bg-red-500' : 'bg-gray-600 hover:bg-red-500' }} transition-colors"
                                            title="Absent">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex gap-2">
                                    <button onclick="openEditStudentModal({{ $student->id }}, '{{ $student->name }}')" 
                                            class="px-3 py-1 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90 transition-opacity">
                                        Edit
                                    </button>
                                    <button onclick="deleteStudent({{ $student->id }})" 
                                            class="px-3 py-1 border-2 border-pink-500 text-white rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center relative z-10">
            <div class="mb-4">
                <i class="fi fi-rr-users text-pink-500 text-5xl"></i>
            </div>
            <h3 class="text-white text-xl font-semibold mb-2">No Students Added Yet</h3>
            <p class="text-gray-400 mb-4">Start by adding students to this classroom</p>
            <button type="button" onclick="openAddStudentModal()" 
                    class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity cursor-pointer relative z-10">
                Add First Student
            </button>
        </div>
        @endif
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden">
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

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
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
    </div>

    @push('scripts')
    <script>
        // Define functions in the global scope
        window.openAddStudentModal = function() {
            console.log('Opening add student modal');
            const modal = document.getElementById('addStudentModal');
            if (!modal) {
                console.error('Add student modal not found');
                return;
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeAddStudentModal = function() {
            console.log('Closing add student modal');
            const modal = document.getElementById('addStudentModal');
            if (!modal) {
                console.error('Add student modal not found');
                return;
            }
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset form
            const form = document.getElementById('addStudentForm');
            if (form) form.reset();
        };

        window.showToast = function(message, isError = false) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            // Update toast appearance based on message type
            if (isError) {
                toast.classList.remove('bg-pink-500');
                toast.classList.add('bg-red-500');
            } else {
                toast.classList.remove('bg-red-500');
                toast.classList.add('bg-pink-500');
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('animate-slide-in');
            
            setTimeout(() => {
                toast.classList.add('animate-fade-out');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('animate-slide-in', 'animate-fade-out');
                }, 300);
            }, 3000);
        };

        window.submitAddStudent = function(event) {
            event.preventDefault();
            console.log('Submitting add student form');
            
            const form = event.target;
            const formData = new FormData(form);
            const name = formData.get('name');
            
            if (!name) {
                showToast('Please enter a student name', true);
                return;
            }

            const data = {
                name: name,
                classroom_id: {{ $classroom->id }}
            };

            console.log('Sending request with data:', data);
            
            fetch('{{ route("students.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to add student');
                    }
                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    closeAddStudentModal();
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to add student');
                }
            })
            .catch(error => {
                console.error('Error adding student:', error);
                showToast(error.message || 'An error occurred while adding student', true);
            });
        };

        function updateAttendance(studentId, status) {
            fetch('{{ route("attendance.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    classroom_id: {{ $classroom->id }},
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Attendance updated successfully');
                    location.reload();
                }
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: formData.get('name')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Student updated successfully');
                    location.reload();
                }
            });
        }

        window.deleteStudent = function(studentId) {
            Swal.fire({
                title: 'Delete Student',
                text: "Are you sure you want to delete this student? This action cannot be undone.",
                icon: null,
                showCancelButton: true,
                confirmButtonColor: '#ec4899',
                cancelButtonColor: 'transparent',
                confirmButtonText: 'Delete Student',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'bg-[#211F27] border border-pink-500/20 rounded-lg',
                    title: 'text-2xl font-semibold text-white mb-4',
                    htmlContainer: 'text-gray-400',
                    confirmButton: 'px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity',
                    cancelButton: 'px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all',
                    actions: 'gap-2'
                },
                background: '#211F27',
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/students/${studentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to delete student');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting student:', error);
                        showToast(error.message || 'An error occurred while deleting student', true);
                    });
                }
            });
        };

        // Initialize when the document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            // Add form submit handler
            const addStudentForm = document.getElementById('addStudentForm');
            if (addStudentForm) {
                addStudentForm.addEventListener('submit', submitAddStudent);
            }

            // Add click handlers to buttons
            const addButtons = document.querySelectorAll('button[onclick="openAddStudentModal()"]');
            console.log('Found add buttons:', addButtons.length);
            
            addButtons.forEach(button => {
                console.log('Adding click listener to button:', button);
                button.addEventListener('click', function(e) {
                    console.log('Button clicked');
                    e.preventDefault();
                    openAddStudentModal();
                });
            });
        });
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
    </style>
    @endpush

    <!-- Add SweetAlert2 for better confirmation dialogs -->
    @push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
</x-layout>