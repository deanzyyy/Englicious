<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-[#211F27] rounded-lg p-6 shadow-lg">
            <h2 class="text-2xl font-bold text-pink-500 mb-6">{{ $classroom->name }} - Grades</h2>

            @if($submissions->isEmpty())
                <div class="text-gray-400 text-center py-8">
                    No exercises have been completed yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-[#2A2A32] rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                    Exercise Title
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                    Total Questions
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                    Correct Answers
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-pink-500 uppercase tracking-wider">
                                    Completed At
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($submissions as $submission)
                                <tr class="hover:bg-[#32323A] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                        {{ $submission->exercise->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $submission->total_questions }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $submission->correct_answers }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-orange-500 font-medium">
                                            {{ number_format($submission->score, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        @php
                                            $updatedAt = $submission->updated_at;
                                            $formatted = '';
                                            try {
                                                if ($updatedAt instanceof \Carbon\Carbon || $updatedAt instanceof \Illuminate\Support\Carbon) {
                                                    $formatted = $updatedAt->format('M d, Y H:i');
                                                } elseif (is_string($updatedAt) && strtotime($updatedAt)) {
                                                    $formatted = \Carbon\Carbon::parse($updatedAt)->format('M d, Y H:i');
                                                } else {
                                                    $formatted = $updatedAt ?: '-';
                                                }
                                            } catch (\Throwable $e) {
                                                $formatted = is_string($updatedAt) ? $updatedAt : '-';
                                            }
                                        @endphp
                                        {{ $formatted }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layout> 