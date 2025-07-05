@extends('classroom.show')

@section('content')
<div class="container py-10">
    <h1 class="text-2xl font-bold text-white mb-6">Review Essay Answers - {{ $exercise->title }}</h1>
    <div class="mb-6 flex flex-wrap items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-4 items-center mb-0">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search student..." class="p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none">
            <select name="status" class="p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none">
                <option value="">All</option>
                <option value="reviewed" @if(($status ?? '')==='reviewed') selected @endif>Reviewed</option>
                <option value="unreviewed" @if(($status ?? '')==='unreviewed') selected @endif>Unreviewed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90">Filter</button>
        </form>
        <div class="flex flex-wrap gap-4 items-center">
            <form method="POST" action="{{ route('classroom.exercise.review.archive', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" class="mb-0">
                @csrf
                <button type="submit" class="px-4 py-2 bg-none border-2 border-pink-500 text-pink-500 rounded hover:bg-pink-500 hover:text-white transition">Archive</button>
            </form>
            <a href="{{ route('classroom.exercise.review.export', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" class="px-4 py-2 bg-none border-2 border-pink-500 text-pink-500 rounded hover:bg-pink-500 hover:text-white transition">Export to PDF</a>
            <a href="{{ route('classroom.archive.list') }}" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded hover:opacity-90">View Archive</a>
        </div>
    </div>
    @if(session('success'))
        <div class="text-green-400 mb-2">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('classroom.exercise.review.submit', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" x-data="{ locked: false }">
        @csrf
        <div class="space-y-4">
            @forelse($submissions as $submission)
                <div x-data="{ open: false }" class="border border-pink-500/20 rounded-lg bg-[#211F27]">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 focus:outline-none">
                        <span class="text-lg font-semibold text-pink-500">{{ $submission->user->name ?? 'Unknown' }}</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-pink-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-6">
                        <table class="min-w-full bg-[#18161d] rounded-lg mt-2">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-300">Essay Question</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Answer</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Score</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Comment</th>
                                    <th class="px-4 py-2 text-left text-gray-300">Lock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($essayQuestions as $question)
                                    @php
                                        $answer = isset($submission->answers[$question->id]) && trim($submission->answers[$question->id]) !== ''
                                            ? $submission->answers[$question->id]
                                            : '<span class="italic text-gray-500">(No answer provided)</span>';
                                        $score = isset($submission->essay_scores[$question->id]) ? $submission->essay_scores[$question->id] : '';
                                        $comment = isset($submission->essay_comments[$question->id]) ? $submission->essay_comments[$question->id] : '';
                                    @endphp
                                    <tr x-data="{ locked: false }">
                                        <td class="px-4 py-2 text-white">{{ $question->question_text }}</td>
                                        <td class="px-4 py-2 text-gray-200">{!! $answer !!}</td>
                                        <td class="px-4 py-2">
                                            <input type="number" name="scores[{{ $question->id }}][{{ $submission->user_id }}]" min="0" max="100" value="{{ $score }}" class="w-20 p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none" placeholder="Score" :disabled="locked">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" name="comments[{{ $question->id }}][{{ $submission->user_id }}]" value="{{ $comment }}" class="w-full p-2 rounded bg-gray-800 text-white border border-pink-500 focus:outline-none" placeholder="Comment" :disabled="locked">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="locked = !locked" :aria-label="locked ? 'Unlock scoring and comment' : 'Lock scoring and comment'" class="p-2 rounded-full bg-gradient-to-r from-pink-500 to-orange-500 text-white focus:outline-none">
                                                <svg x-show="locked" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a2 2 0 100-4 2 2 0 000 4zm6-6V9a6 6 0 10-12 0v2a2 2 0 00-2 2v7a2 2 0 002 2h12a2 2 0 002-2v-7a2 2 0 00-2-2z" />
                                                </svg>
                                                <svg x-show="!locked" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11V9a5 5 0 00-10 0v2m12 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2h10a2 2 0 012 2z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-gray-400">No submissions found.</div>
            @endforelse
        </div>
        @if($submissions->count() > 0)
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-pink-500 to-orange-500 text-white font-semibold hover:opacity-90 transition">Save Review</button>
        </div>
        @endif
    </form>
    <div class="mt-8">
        {{ $submissions->appends(request()->except('page'))->links() }}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection 