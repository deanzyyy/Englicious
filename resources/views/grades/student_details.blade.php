<div>
    <h4 class="text-lg font-bold text-pink-500 mb-4">Detail Nilai Siswa</h4>
    <div class="mb-4">
        <span class="font-semibold text-white">Nama:</span> <span class="text-gray-300">{{ $student->name }}</span><br>
        <span class="font-semibold text-white">Email:</span> <span class="text-gray-300">{{ $student->email }}</span>
    </div>
    <div class="mb-6">
        <h5 class="font-semibold text-pink-400 mb-2">Nilai Exercise</h5>
        @if($exerciseSubmissions->count() > 0)
            <table class="w-full text-sm mb-2">
                <thead>
                    <tr class="text-gray-400">
                        <th class="py-1 px-2 text-left">Judul</th>
                        <th class="py-1 px-2 text-left">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exerciseSubmissions as $ex)
                        <tr>
                            <td class="py-1 px-2 text-white">{{ $ex->exercise->title ?? '-' }}</td>
                            <td class="py-1 px-2 text-white">{{ $ex->score !== null ? $ex->score : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-400">Belum ada exercise yang dinilai.</p>
        @endif
    </div>
    <div class="mb-6">
        <h5 class="font-semibold text-pink-400 mb-2">Nilai Assignment</h5>
        @if($assignmentSubmissions->count() > 0)
            <table class="w-full text-sm mb-2">
                <thead>
                    <tr class="text-gray-400">
                        <th class="py-1 px-2 text-left">Judul</th>
                        <th class="py-1 px-2 text-left">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignmentSubmissions as $as)
                        <tr>
                            <td class="py-1 px-2 text-white">{{ $as->assignment->title ?? '-' }}</td>
                            <td class="py-1 px-2 text-white">{{ $as->grade !== null ? $as->grade : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-400">Belum ada assignment yang dinilai.</p>
        @endif
    </div>
    <div class="mb-2">
        <span class="font-semibold text-white">Rata-rata Exercise:</span> <span class="text-gray-300">{{ $avgExercise !== null ? number_format($avgExercise, 2) : '-' }}</span><br>
        <span class="font-semibold text-white">Rata-rata Assignment:</span> <span class="text-gray-300">{{ $avgAssignment !== null ? number_format($avgAssignment, 2) : '-' }}</span><br>
        <span class="font-semibold text-white">Nilai Akhir:</span> <span class="text-pink-400 font-bold">{{ $finalScore !== null ? number_format($finalScore, 2) : '-' }}</span>
    </div>
</div> 