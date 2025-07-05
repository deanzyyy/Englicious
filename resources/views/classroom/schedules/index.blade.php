@extends('classroom.show')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-pink-500">Schedule</h2>
        @if(Auth::check() && Auth::user()->role === 'teacher' && Auth::user()->id === $classroom->teacher_id)
            <button onclick="openScheduleModal()" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-all text-sm font-medium">+ Add Schedule</button>
        @endif
    </div>
    <div class="bg-[#211F27] rounded-lg shadow-lg p-6">
        @if($schedules->count() > 0)
            <ul class="divide-y divide-gray-700">
                @foreach($schedules as $schedule)
                    <li class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <div class="text-lg font-semibold text-white">{{ $schedule->title }}</div>
                            <div class="text-gray-400 text-sm mb-1">{{ $schedule->date }} | {{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</div>
                            <div class="text-gray-300 text-sm">{{ $schedule->description }}</div>
                        </div>
                        @if(Auth::check() && Auth::user()->role === 'teacher' && Auth::user()->id === $classroom->teacher_id)
                        <div class="flex gap-2 mt-2 md:mt-0">
                            <button onclick="editSchedule({{ $schedule->id }}, '{{ addslashes($schedule->title) }}', '{{ addslashes($schedule->description) }}', '{{ $schedule->date }}', '{{ substr($schedule->start_time,0,5) }}', '{{ substr($schedule->end_time,0,5) }}')" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Edit</button>
                            <form action="{{ route('classroom.schedules.destroy', [$classroom->name, $schedule->id]) }}" method="POST" onsubmit="return confirm('Delete this schedule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Delete</button>
                            </form>
                        </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-400 text-center py-8">No schedule yet.</div>
        @endif
    </div>

    <!-- Modal Add/Edit Schedule -->
    <div id="schedule-modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-[#211F27] p-8 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
            <h3 class="text-xl font-semibold text-white mb-4" id="schedule-modal-title">Add Schedule</h3>
            <form id="schedule-form" method="POST">
                @csrf
                <input type="hidden" name="_method" id="schedule-method" value="POST">
                <div class="mb-4">
                    <label class="block text-white mb-2">Title</label>
                    <input type="text" name="title" id="schedule-title" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-white mb-2">Description</label>
                    <textarea name="description" id="schedule-description" rows="3" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required></textarea>
                </div>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-white mb-2">Date</label>
                        <input type="date" name="date" id="schedule-date" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div>
                        <label class="block text-white mb-2">Start Time</label>
                        <input type="time" name="start_time" id="schedule-start-time" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                    <div>
                        <label class="block text-white mb-2">End Time</label>
                        <input type="time" name="end_time" id="schedule-end-time" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 border-2 border-pink-500 text-pink-500 hover:bg-red-500 hover:text-white hover:border-0 rounded transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openScheduleModal() {
        document.getElementById('schedule-modal').classList.remove('hidden');
        document.getElementById('schedule-modal-title').textContent = 'Add Schedule';
        document.getElementById('schedule-form').reset();
        document.getElementById('schedule-form').action = "{{ route('classroom.schedules.store', $classroom->name) }}";
        document.getElementById('schedule-method').value = 'POST';
    }
    function closeScheduleModal() {
        document.getElementById('schedule-modal').classList.add('hidden');
    }
    function editSchedule(id, title, description, date, start, end) {
        openScheduleModal();
        document.getElementById('schedule-modal-title').textContent = 'Edit Schedule';
        document.getElementById('schedule-title').value = title;
        document.getElementById('schedule-description').value = description;
        document.getElementById('schedule-date').value = date;
        document.getElementById('schedule-start-time').value = start;
        document.getElementById('schedule-end-time').value = end;
        document.getElementById('schedule-form').action = "{{ url('classroom/'.$classroom->name.'/schedules') }}/" + id;
        document.getElementById('schedule-method').value = 'PUT';
    }
    // Modal close on background click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'schedule-modal') closeScheduleModal();
    });
</script>
@endpush 