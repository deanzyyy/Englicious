<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    @vite('resources/css/app.css')
  </head>
  <body class="bg-[#101014]">
    <div class="flex min-h-screen">
        <x-sidebar></x-sidebar>

        <div class="ml-64 p-10 w-full">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white">Attendance</h1>
                <p class="text-gray-400 text-lg mt-2">Manage attendance for all your classrooms</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($classrooms as $classroom)
                <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-6 hover:border-pink-500/50 transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-white">{{ $classroom->name }}</h3>
                            <p class="text-gray-400 text-sm mt-1">{{ $classroom->students->count() }} Students</p>
                        </div>
                        <div class="bg-pink-500/10 rounded-full p-2">
                            <i class="fi fi-rr-users-class text-2xl text-pink-500"></i>
            </div>
        </div>

                    <div class="flex justify-between items-center mt-6">
                                    <div class="flex gap-2">
                            <span class="px-2 py-1 bg-green-500/10 text-green-500 text-xs rounded-full">
                                {{ $classroom->attendances->where('status', 'present')->count() }} Present
                            </span>
                            <span class="px-2 py-1 bg-red-500/10 text-red-500 text-xs rounded-full">
                                {{ $classroom->attendances->where('status', 'absent')->count() }} Absent
                            </span>
                        </div>
                        <a href="{{ route('attendance.show', $classroom->name) }}" 
                           class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white text-sm rounded-lg hover:opacity-90 transition-opacity">
                            View Attendance
                        </a>
                        </div>
                </div>
                @empty
                <div class="col-span-3">
                    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center">
                        <div class="mb-4">
                            <i class="fi fi-rr-users text-pink-500 text-5xl"></i>
                        </div>
                        <h3 class="text-white text-xl font-semibold mb-2">No Classrooms Available</h3>
                        <p class="text-gray-400 mb-4">Create a classroom to start managing attendance</p>
                        <a href="{{ route('classroom.list') }}" 
                           class="inline-block px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Create Classroom
                        </a>
                        </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
  </body>
</html> 