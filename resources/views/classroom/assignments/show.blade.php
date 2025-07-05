@extends('classroom.show')

@section('content')
<div class="container max-w-xl mx-auto py-10">
    <div class="bg-[#211F27] p-8 rounded-lg mb-8">
        <h1 class="text-white text-2xl font-bold mb-2">{{ $assignment->title }}</h1>
        @if($assignment->description)
            <p class="text-gray-300 mb-4">{{ $assignment->description }}</p>
        @endif
        <p class="text-gray-400 mb-2">Batas Pengumpulan: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y H:i') }}</p>
        @php
            $now = \Carbon\Carbon::now();
            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
            $diff = $now->diffInSeconds($dueDate, false);
        @endphp

        @if(Auth::check() && Auth::user()->role === 'student')
            @if($diff > 0)
                <p class="text-yellow-400 mb-4">Sisa waktu: <span id="time-remaining"></span></p>
            @else
                <p class="text-red-500 mb-4">Waktu pengumpulan sudah berakhir!</p>
            @endif
        @endif

        @if($assignment->file_path)
            <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="text-pink-500 underline">Download File Assignment</a>
        @endif
    </div>
    {{-- Tampilkan form upload tugas hanya untuk siswa --}}
    @if(Auth::check() && Auth::user()->role === 'student')
    <div class="bg-[#211F27] p-8 rounded-lg">
        <h2 class="text-white text-lg font-semibold mb-4">Upload Tugas Anda</h2>
        <form action="{{ route('classroom.assignments.submit', [$classroom->name, $assignment->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-white mb-2">File Tugas (PDF/DOCX)</label>
                <input type="file" name="student_file" accept=".pdf,.doc,.docx" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" {{ $diff <= 0 ? 'disabled' : '' }} required>
            </div>
            <div class="flex justify-end">
                <button type="submit" id="submit-button" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity {{ $diff <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $diff <= 0 ? 'disabled' : '' }}>Kumpulkan Tugas</button>
            </div>
        </form>
    </div>
    @elseif(Auth::check() && Auth::user()->role === 'teacher')
        <div class="bg-[#211F27] p-8 rounded-lg">
            <h2 class="text-white text-lg font-semibold mb-4">Daftar Pengumpulan Tugas Siswa</h2>
            @if($assignment->submissions->count() > 0)
                <ul class="space-y-4">
                    @foreach($assignment->submissions as $submission)
                        <li class="flex justify-between items-center bg-[#1a1a1f] p-4 rounded-lg">
                            <span class="text-white">{{ $submission->user->name }}</span>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-pink-500 underline">Download Submission</a>
                            <form action="{{ route('classroom.assignments.submissions.grade', [$classroom->name, $assignment->id, $submission->id]) }}" method="POST" class="flex items-center gap-2 ml-4">
                                @csrf
                                <input type="number" name="grade" min="0" max="100" step="0.01" value="{{ $submission->grade }}" class="w-20 p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none" placeholder="Nilai">
                                <button type="submit" class="px-3 py-1 bg-pink-500 text-white rounded hover:bg-pink-600">Simpan</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-400">Belum ada siswa yang mengumpulkan tugas.</p>
            @endif
        </div>
    @endif
</div>

@if(Auth::check() && Auth::user()->role === 'student')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dueDateString = "{{ \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d H:i:s') }}";
        const dueDate = new Date(dueDateString.replace(/-/g, '/')); // Handle different date formats
        const timeRemainingElement = document.getElementById('time-remaining');
        const submitButton = document.getElementById('submit-button');

        function updateCountdown() {
            const now = new Date();
            const timeLeft = dueDate.getTime() - now.getTime();

            if (timeLeft <= 0) {
                timeRemainingElement.innerText = 'Waktu habis!';
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                Swal.fire({
                    title: 'Maaf!',
                    text: 'Kamu sudah terlambat mengumpulkan tugas ini.',
                    icon: 'warning',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493',
                });
                clearInterval(countdownInterval);
            } else {
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                timeRemainingElement.innerText = `${days} hari, ${hours} jam, ${minutes} menit, ${seconds} detik`;
            }
        }

        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    });
</script>
@endif

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493',
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493',
        });
    });
</script>
@endif
@endsection 