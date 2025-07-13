<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
  </head>
  <body class="bg-[#101014]">
    
    <div class="flex min-h-screen">
        <x-sidebar></x-sidebar>

        <div class="ml-64 p-10 w-full">
            <div class="judul flex justify-between">
                <div>
                    <h1 class="text-white text-4xl font-bold">{{ $classroom->name }}</h1>
                    @php
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
                    @endphp
                    <p class="text-gray-400 text-lg mt-2">{{ $desc }}</p>
                </div>
                @if (Auth::check() && Auth::user()->role === 'student')
                <div class="flex gap-2">
                    <a href="{{ route('home') }}" class="w-25 h-10 flex items-center justify-center text-white bg-gradient-to-r from-gray-500 to-gray-700 rounded-lg hover:bg-none hover:border-2 hover:border-white transition-all duration-300">
                        <i class="fi fi-rr-arrow-left text-lg mr-2"></i>Back
                    </a>
                    <form id="leave-classroom-form" action="{{ route('classroom.leave', $classroom->name) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmLeaveClassroom()" class="w-25 h-10 text-white bg-gradient-to-r from-pink-500 to-orange-500 rounded-lg hover:bg-none hover:border-2 hover:border-white transition-all duration-300">Exit</button>
                </div>
                @endif
            </div>
        
            <div class="mt-10 flex w-full">
                <div class="flex space-x-6">
                    <a href="{{ route('classroom.materials', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/materials') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Materials</a>
                    <a href="{{ route('classroom.exercises', $classroom->name) }}" 
                        class="{{ request()->routeIs('classroom.exercises') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Exercise</a>
                    <a href="{{ route('classroom.assignments.index', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/assignments') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Assignments</a>
                    <a href="{{ route('classroom.schedules.index', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/schedules') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Schedule</a>
                    <a href="{{ route('classroom.presence', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/attendance') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Presence</a>
                    <a href="{{ route('classroom.grades', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/grades') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Grade</a>
                    <a href="{{ route('classroom.games', $classroom->name) }}" 
                        class="{{ request()->is('classroom/'.$classroom->name.'/games*') ? 'bg-gradient-to-r from-pink-500/20 to-transparent border-l-4 border-pink-500 text-pink-500' : 'border-l-4 border-transparent text-gray-400 hover:border-pink-500 hover:text-pink-500'}} relative px-6 py-2 uppercase tracking-wider text-sm font-medium transition-all duration-300">Games</a>
                </div>
            </div>

            <div class="border-b-2 border-gray-500 pt-5 w-full"></div>
        
            <div>
                @if(request()->is('classroom/'.$classroom->name.'/materials'))
                    @yield('content')
                @elseif(request()->is('classroom/'.$classroom->name.'/exercises'))
                    @yield('content')
                @elseif(request()->is('classroom/'.$classroom->name.'/assignments') || request()->is('classroom/'.$classroom->name.'/assignments/*'))
                    @yield('content')
                @elseif(request()->is('classroom/'.$classroom->name.'/grades'))
                    @yield('content')
                @elseif(request()->is('classroom/'.$classroom->name.'/games*'))
                    @yield('content')
                @elseif(request()->is('classroom/'.$classroom->name.'/presence'))
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Student Attendance</h2>
                                <p class="text-gray-400 text-lg mt-2">Manage attendance for {{ Carbon\Carbon::today()->format('l, d F Y') }}</p>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="openAddStudentModal()" 
                                    class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center cursor-pointer">
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

                        @if($classroom->students->count() > 0)
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
                                        @foreach($classroom->students as $index => $student)
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
                        @endif

                        <!-- Add Student Modal -->
                        @include('components.modals.add-student-modal')

                        <!-- Edit Student Modal -->
                        @include('components.modals.edit-student-modal')
                    </div>
                @else
                    @if(Auth::check() && Auth::user()->role === 'student' && request()->is('classroom/'.$classroom->name))
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-white mb-4" style="margin-top: 24px; margin-bottom: 12px;">Hello, <span class="text-pink-500">{{ Auth::user()->name }}</span>.</h2>
                            <p class="text-gray-300 mb-8">Welcome back. Ready to continue your learning journey?</p>
                            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                                <h3 class="text-xl font-semibold text-white mb-2">Latest Exercise</h3>
                                @php
                                    $todayExercise = $classroom->exercises->where('created_at', '>=', \Carbon\Carbon::today())->first();
                                @endphp
                                @if($todayExercise)
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <div class="text-lg text-pink-500 font-bold">{{ $todayExercise->title }}</div>
                                            <div class="text-gray-400 text-sm mb-2">Uploaded at {{ $todayExercise->created_at->format('d M Y H:i') }}</div>
                                            <div class="text-gray-300">{{ $todayExercise->description }}</div>
                                        </div>
                                        <div class="mt-4 md:mt-0">
                                            <a href="{{ route('classroom.exercises', $classroom->name) }}" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90 transition">Go to Exercises</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-gray-400">No exercises have been uploaded yet.</div>
                                @endif
                            </div>
                            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                                <h3 class="text-xl font-semibold text-white mb-2">Exercises you haven't done yet</h3>
                                @if($incompleteExercises->count() > 0)
                                    <ul class="list-disc pl-6">
                                        @foreach($incompleteExercises as $exercise)
                                            <li class="mb-1 flex items-center justify-between">
                                                <div>
                                                    <span class="text-pink-500 font-semibold">{{ $exercise->title }}</span>
                                                    <span class="text-gray-400">- {{ $exercise->description }}</span>
                                                </div>
                                                @if(isset($todayExercise) && $todayExercise && $todayExercise->id === $exercise->id)
                                                    <a href="{{ route('classroom.exercise.take', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" class="ml-4 px-4 py-1 bg-none text-pink-500 border-2 border-pink-500 rounded hover:bg-pink-500 hover:text-white transition">Complete now!</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-green-400">You have completed all exercises. Great job.</div>
                                @endif
                            </div>
                        </div>
                    @endif
                    @yield('content')
                @endif
            </div>
        </div>
    </div>

    {{-- All JavaScript functions for this page --}}
    <script>
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
            
            fetch('{{ route("students.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    classroom_id: {{ $classroom->id }}
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    @stack('scripts')
  </body>
</html> 