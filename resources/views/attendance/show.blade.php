<x-layout>
    <div class="min-h-screen bg-[#101014] p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-white">{{ $classroom->name }}</h1>
                    <p class="text-gray-400 text-lg mt-2">Manage attendance for {{ Carbon\Carbon::today()->format('l, d F Y') }}</p>
                </div>
                <div class="flex gap-4">
                    <button onclick="openAddStudentModal()" 
                            class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                        <i class="fi fi-rr-plus"></i>
                        Add Student
                    </button>
                    <a href="{{ route('attendance.history', $classroom) }}" 
                       class="px-6 py-3 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center gap-2">
                        <i class="fi fi-rr-time-past"></i>
                        View History
                    </a>
                    <a href="{{ route('attendance.export-pdf', $classroom) }}" 
                       class="px-6 py-3 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all flex items-center gap-2">
                        <i class="fi fi-rr-file-pdf"></i>
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-[#1E1E24] rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-4 px-6 text-left text-gray-400">No</th>
                            <th class="py-4 px-6 text-left text-gray-400">Student Name</th>
                            <th class="py-4 px-6 text-center text-gray-400">Status</th>
                            <th class="py-4 px-6 text-center text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($students as $index => $student)
                            <tr class="hover:bg-gray-800/50">
                                <td class="py-4 px-6 text-white">{{ $index + 1 }}</td>
                                <td class="py-4 px-6 text-white">{{ $student->name }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex justify-center gap-2">
                                        @php
                                            $attendance = $attendances->get($student->id);
                                            $status = $attendance ? $attendance->status : null;
                                        @endphp
                                        <button onclick="updateAttendance('{{ $student->id }}', 'present')" 
                                                class="w-10 h-10 rounded-full {{ $status === 'present' ? 'bg-green-500' : 'bg-gray-700 hover:bg-green-500/20' }} flex items-center justify-center transition-colors"
                                                title="Present">
                                            <i class="fi fi-rr-check text-white"></i>
                                        </button>
                                        <button onclick="updateAttendance('{{ $student->id }}', 'late')" 
                                                class="w-10 h-10 rounded-full {{ $status === 'late' ? 'bg-blue-500' : 'bg-gray-700 hover:bg-blue-500/20' }} flex items-center justify-center transition-colors"
                                                title="Late">
                                            <i class="fi fi-rr-time-forward text-white"></i>
                                        </button>
                                        <button onclick="updateAttendance('{{ $student->id }}', 'excused')" 
                                                class="w-10 h-10 rounded-full {{ $status === 'excused' ? 'bg-yellow-500' : 'bg-gray-700 hover:bg-yellow-500/20' }} flex items-center justify-center transition-colors"
                                                title="Excused">
                                            <i class="fi fi-rr-note text-white"></i>
                                        </button>
                                        <button onclick="updateAttendance('{{ $student->id }}', 'absent')" 
                                                class="w-10 h-10 rounded-full {{ $status === 'absent' ? 'bg-red-500' : 'bg-gray-700 hover:bg-red-500/20' }} flex items-center justify-center transition-colors"
                                                title="Absent">
                                            <i class="fi fi-rr-cross text-white"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="editStudent('{{ $student->id }}', '{{ $student->name }}')"
                                                class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                                            Edit
                                        </button>
                                        <button onclick="deleteStudent('{{ $student->id }}')"
                                                class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-red-500 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fi fi-rr-users text-4xl mb-2"></i>
                                        <p class="text-lg">No students available</p>
                                        <p class="text-sm">Click "Add Student" to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
        <div class="bg-[#1E1E24] rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-white mb-4">Add New Student</h2>
            <form id="addStudentForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-400 mb-2">Student Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeAddStudentModal()" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
        <div class="bg-[#1E1E24] rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-white mb-4">Edit Student</h2>
            <form id="editStudentForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="student_id" id="editStudentId">
                <div>
                    <label class="block text-gray-400 mb-2">Student Name</label>
                    <input type="text" name="name" id="editStudentName" class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeEditStudentModal()" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
        <div class="bg-[#1E1E24] rounded-lg p-6 w-full max-w-md">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fi fi-rr-trash text-red-500 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Delete Student</h2>
                <p class="text-gray-400 mb-6">Are you sure you want to delete this student? This action cannot be undone.</p>
                <div class="flex justify-center gap-4">
                    <button onclick="closeDeleteModal()" class="px-6 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</button>
                    <button onclick="confirmDelete()" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let studentToDelete = null;

        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
            document.getElementById('addStudentModal').classList.add('flex');
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
            document.getElementById('addStudentModal').classList.remove('flex');
            document.getElementById('addStudentForm').reset();
        }

        function editStudent(studentId, studentName) {
            document.getElementById('editStudentId').value = studentId;
            document.getElementById('editStudentName').value = studentName;
            document.getElementById('editStudentModal').classList.remove('hidden');
            document.getElementById('editStudentModal').classList.add('flex');
        }

        function closeEditStudentModal() {
            document.getElementById('editStudentModal').classList.add('hidden');
            document.getElementById('editStudentModal').classList.remove('flex');
            document.getElementById('editStudentForm').reset();
        }

        function deleteStudent(studentId) {
            studentToDelete = studentId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            studentToDelete = null;
        }

        function confirmDelete() {
            if (!studentToDelete) return;
            
            fetch(`/classroom/{{ $classroom->id }}/students/${studentToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the student');
            })
            .finally(() => {
                closeDeleteModal();
            });
        }

        function updateAttendance(studentId, status) {
            fetch('{{ route("attendance.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    classroom_id: '{{ $classroom->id }}',
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update attendance: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating attendance');
            });
        }

        // Handle Add Student Form
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("classroom.students.add", $classroom->name) }}', {
                method: 'POST',
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
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the student');
            })
            .finally(() => {
                closeAddStudentModal();
            });
        });

        // Handle Edit Student Form
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const studentId = formData.get('student_id');

            fetch(`/classroom/{{ $classroom->name }}/students/${studentId}`, {
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
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the student');
            })
            .finally(() => {
                closeEditStudentModal();
            });
        });
    </script>
</x-layout> 