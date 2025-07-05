@extends('layouts.app')

@section('content')
<div class="ml-64 p-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Edit Game</h1>
        <p class="text-gray-400">Update game settings and configuration</p>
    </div>

    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
        <div class="p-6">
            <form method="POST" action="{{ route('games.update', $game) }}">
                @csrf
                @method('PUT')
                
                <!-- Basic Game Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Name</label>
                        <input type="text" name="name" value="{{ old('name', $game->name) }}" required
                               class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                               placeholder="Enter game name...">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Classroom</label>
                        <select name="classroom_id" required
                                class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                            <option value="">Select Classroom</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ old('classroom_id', $game->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classroom_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Game Mode and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Mode</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="mode" value="individual" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       {{ old('mode', $game->mode) === 'individual' ? 'checked' : '' }}>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Individual</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="mode" value="group" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       {{ old('mode', $game->mode) === 'group' ? 'checked' : '' }}>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Group</span>
                            </label>
                        </div>
                        @error('mode')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-pink-500 text-sm font-medium mb-2">Game Type</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="type" value="offline" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       {{ old('type', $game->type) === 'offline' ? 'checked' : '' }}>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Offline</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="radio" name="type" value="online" required 
                                       class="text-pink-500 focus:ring-pink-500 border-2 border-pink-500/20"
                                       {{ old('type', $game->type) === 'online' ? 'checked' : '' }}>
                                <span class="text-white group-hover:text-pink-500 transition-colors">Online</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Exercise Selection -->
                <div class="mb-6">
                    <label class="block text-pink-500 text-sm font-medium mb-2">Select Exercise</label>
                    <select name="exercise_id" required
                            class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                        <option value="">Select Exercise</option>
                        @foreach($exercises as $exercise)
                            <option value="{{ $exercise->id }}" {{ old('exercise_id', $game->exercise_id) == $exercise->id ? 'selected' : '' }}>
                                {{ $exercise->title }} ({{ $exercise->questions_count }} questions)
                            </option>
                        @endforeach
                    </select>
                    @error('exercise_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Game Status -->
                <div class="mb-6">
                    <label class="block text-pink-500 text-sm font-medium mb-2">Current Status</label>
                    <div class="p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20">
                        <div class="flex items-center justify-between">
                            <span class="text-white">Status: {{ ucfirst($game->status) }}</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($game->status === 'ongoing') bg-green-500/20 text-green-400
                                @elseif($game->status === 'finished') bg-purple-500/20 text-purple-400
                                @else bg-yellow-500/20 text-yellow-400 @endif">
                                {{ ucfirst($game->status) }}
                            </span>
                        </div>
                        @if($game->status === 'draft')
                            <p class="text-gray-400 text-sm mt-2">This game is ready to be started.</p>
                        @elseif($game->status === 'ongoing')
                            <p class="text-gray-400 text-sm mt-2">This game is currently being played.</p>
                        @else
                            <p class="text-gray-400 text-sm mt-2">This game has been completed.</p>
                        @endif
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Update Game
                    </button>
                    <a href="{{ route('games.index') }}" 
                       class="px-6 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 