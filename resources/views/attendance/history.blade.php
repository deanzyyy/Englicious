@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#101014]">
    {{-- <x-sidebar></x-sidebar> --}}

    <div class="flex-1 p-8 w-full">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">{{ $classroom->name }} - Attendance History</h1>
                <p class="text-gray-400">Last 30 Days</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('classroom.presence', $classroom->name) }}" 
                   class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                    Back to Attendance
                </a>
                <a href="{{ route('attendance.export-pdf', $classroom->id) }}" target="_blank"
                   class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Export PDF
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-[#211F27] rounded-lg p-4 border border-white/20">
                <div class="text-2xl font-bold text-green-500 mb-2">{{ $statistics['present'] }}</div>
                <div class="text-gray-400">Present</div>
            </div>
            <div class="bg-[#211F27] rounded-lg p-4 border border-white/20">
                <div class="text-2xl font-bold text-yellow-500 mb-2">{{ $statistics['late'] }}</div>
                <div class="text-gray-400">Late</div>
            </div>
            <div class="bg-[#211F27] rounded-lg p-4 border border-white/20">
                <div class="text-2xl font-bold text-blue-500 mb-2">{{ $statistics['excused'] }}</div>
                <div class="text-gray-400">Excused</div>
            </div>
            <div class="bg-[#211F27] rounded-lg p-4 border border-white/20">
                <div class="text-2xl font-bold text-red-500 mb-2">{{ $statistics['absent'] }}</div>
                <div class="text-gray-400">Absent</div>
            </div>
        </div>

        @if($attendances->isEmpty())
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-chart-line text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">No Attendance Records</h3>
                <p class="text-gray-400 mb-4">There are no attendance records available for the last 30 days.</p>
                <a href="{{ route('classroom.presence', $classroom->name) }}" 
                   class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity inline-block">
                    Take Attendance
                </a>
            </div>
        @else
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20">
                <div class="p-6">
                    @foreach($attendances as $date => $dateAttendances)
                    <div class="mb-8 last:mb-0">
                        <h3 class="text-xl font-semibold text-white mb-4">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h3>
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-white/20">
                                    <th class="py-3 px-4 text-gray-400 font-medium">No</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Student Name</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Status</th>
                                    <th class="py-3 px-4 text-gray-400 font-medium">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dateAttendances as $index => $attendance)
                                <tr class="border-b border-white/20 hover:bg-pink-500/5">
                                    <td class="py-4 px-4 text-white">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4 text-white">{{ $attendance->user->name }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            @if($attendance->status === 'present')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                            bg-gradient-to-br from-[#1a1a1a] to-[#2d2d2d]
                                                            shadow-lg border-2 border-emerald-500 from-emerald-500/20 to-emerald-600/20">
                                                    <i class="fi fi-rr-check text-lg text-emerald-500"></i>
                                                </div>
                                                <span class="text-emerald-500 text-sm font-medium">Present</span>
                                            @elseif($attendance->status === 'late')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                            bg-gradient-to-br from-[#1a1a1a] to-[#2d2d2d]
                                                            shadow-lg border-2 border-amber-500 from-amber-500/20 to-amber-600/20">
                                                    <i class="fi fi-rr-time-forward text-lg text-amber-500"></i>
                                                </div>
                                                <span class="text-amber-500 text-sm font-medium">Late</span>
                                            @elseif($attendance->status === 'excused')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                            bg-gradient-to-br from-[#1a1a1a] to-[#2d2d2d]
                                                            shadow-lg border-2 border-sky-500 from-sky-500/20 to-sky-600/20">
                                                    <i class="fi fi-rr-envelope text-lg text-sky-500"></i>
                                                </div>
                                                <span class="text-sky-500 text-sm font-medium">Excused</span>
                                            @else
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                            bg-gradient-to-br from-[#1a1a1a] to-[#2d2d2d]
                                                            shadow-lg border-2 border-rose-500 from-rose-500/20 to-rose-600/20">
                                                    <i class="fi fi-rr-cross-circle text-lg text-rose-500"></i>
                                                </div>
                                                <span class="text-rose-500 text-sm font-medium">Absent</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-400">{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 