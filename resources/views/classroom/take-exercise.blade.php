@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <a href="{{ route('classroom.exercises', $classroom->name) }}" class="flex items-center text-gray-400 hover:text-pink-400 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Exercise List
    </a>
    <h1 class="text-4xl font-bold text-white mb-2">{{ $exercise->title }}</h1>
    <div class="flex flex-wrap items-center text-gray-400 space-x-2 mb-2">
        <span>{{ $exercise->category }}</span>
        @if($exercise->topic)
            <span>&bull;</span>
            <span>{{ $exercise->topic->name }}</span>
        @endif
        @if($exercise->subtopic)
            <span>&bull;</span>
            <span>{{ $exercise->subtopic->name }}</span>
        @endif
    </div>
    <div class="text-gray-300 mb-8">{{ $exercise->description }}</div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('classroom.exercise.submit', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
        @foreach($exercise->questions as $index => $question)
            <div class="mb-8">
                <div class="mb-2 font-semibold text-pink-400">Question {{ $index + 1 }}:</div>
                <div class="mb-3 text-gray-200">{{ $question->question_text }}</div>
                <div class="bg-[#23232b] rounded-xl p-6">
                    @if($question->type === 'multiple_choice' || $question->type === 'optional')
                        <div class="space-y-4">
                            @foreach($question->options as $optionIndex => $option)
                                <label class="flex items-center space-x-3 cursor-pointer w-full">
                                    <input class="form-radio h-5 w-5 text-pink-500 border-gray-600 bg-[#211F27]" type="radio" name="answers[{{ $question->id }}]" value="{{ $optionIndex }}" required>
                                    <span class="block w-full text-gray-200 py-3 px-4 rounded-lg bg-[#292933]">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'true_false')
                        <div class="space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer w-full">
                                <input class="form-radio h-5 w-5 text-pink-500 border-gray-600 bg-[#211F27]" type="radio" name="answers[{{ $question->id }}]" value="true" required>
                                <span class="block w-full text-gray-200 py-3 px-4 rounded-lg bg-[#292933]">True</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer w-full">
                                <input class="form-radio h-5 w-5 text-pink-500 border-gray-600 bg-[#211F27]" type="radio" name="answers[{{ $question->id }}]" value="false" required>
                                <span class="block w-full text-gray-200 py-3 px-4 rounded-lg bg-[#292933]">False</span>
                            </label>
                        </div>
                    @elseif($question->type === 'essay')
                        <textarea name="answers[{{ $question->id }}]" rows="5" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" placeholder="Write your answer here..." required>{{ old('answers.' . $question->id) }}</textarea>
                    @endif
                </div>
            </div>
        @endforeach
        <div class="flex justify-end mt-10">
            <button type="submit" class="px-8 py-3 rounded-lg text-white font-semibold bg-gradient-to-r from-pink-500 to-orange-400 hover:from-pink-600 hover:to-orange-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all text-lg shadow-lg">
                Submit Answers
            </button>
        </div>
    </form>
</div>
@endsection 