@extends('classroom.show')

@section('content')
<div class="mt-8">
    <div class="bg-[#211F27] rounded-lg p-6 shadow-lg">
        <h2 class="text-2xl font-bold text-pink-500 mb-6">{{ $classroom->name }} - My Grades</h2>

        <!-- Grade Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-[#2A2833] rounded-lg p-6 text-center">
                <h3 class="text-gray-400 text-sm font-medium mb-2">Exercise Average</h3>
                <p class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-bold text-3xl">
                    {{ number_format($avgExercise, 2) }}%
                </p>
            </div>
            
            <div class="bg-[#2A2833] rounded-lg p-6 text-center">
                <h3 class="text-gray-400 text-sm font-medium mb-2">Assignment Average</h3>
                @if($avgAssignment !== null)
                    <p class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-bold text-3xl">
                        {{ number_format($avgAssignment, 2) }}%
                    </p>
                @else
                    <p class="text-gray-500 font-bold text-3xl">--</p>
                    <p class="text-gray-500 text-xs mt-2">Menunggu penilaian</p>
                @endif
            </div>
            
            <div class="bg-[#2A2833] rounded-lg p-6 text-center">
                <h3 class="text-gray-400 text-sm font-medium mb-2">Final Score</h3>
                @if($finalScore !== null)
                    <p class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-bold text-3xl">
                        {{ number_format($finalScore, 2) }}%
                    </p>
                @else
                    <p class="text-gray-500 font-bold text-3xl">--</p>
                    <p class="text-gray-500 text-xs mt-2">Menunggu penilaian assignment</p>
                @endif
            </div>
        </div>

        <!-- Exercise Results -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-white mb-4">Exercise Results</h3>
            @if($exerciseSubmissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#2A2833] rounded-lg">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Exercise</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Score</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($exerciseSubmissions as $submission)
                                <tr class="hover:bg-[#32323A] transition-colors">
                                    <td class="px-6 py-4 text-sm text-white">{{ $submission->exercise->title }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-medium">
                                            {{ number_format($submission->score, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $submission->updated_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-gray-400 text-center py-8">
                    <p>No exercises completed yet.</p>
                </div>
            @endif
        </div>

        <!-- Assignment Results -->
        <div>
            <h3 class="text-xl font-semibold text-white mb-4">Assignment Results</h3>
            @if($assignmentSubmissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#2A2833] rounded-lg">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Assignment</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Submitted</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($assignmentSubmissions as $submission)
                                <tr class="hover:bg-[#32323A] transition-colors">
                                    <td class="px-6 py-4 text-sm text-white">{{ $submission->assignment->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $submission->submission_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($submission->grade !== null)
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-medium">
                                                {{ number_format($submission->grade, 2) }}%
                                            </span>
                                        @else
                                            <span class="text-gray-500">Menunggu penilaian</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-gray-400 text-center py-8">
                    <p>No assignments submitted yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 