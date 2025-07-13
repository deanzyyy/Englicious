@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Games Management</h1>
        <p class="text-gray-400">Manage and view all games across classrooms</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-pink-500 text-sm font-medium mb-2">Search Game</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none"
                       placeholder="Search by game name...">
            </div>
            <div>
                <label class="block text-pink-500 text-sm font-medium mb-2">Classroom</label>
                <select name="classroom" class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                    <option value="">All Classrooms</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-pink-500 text-sm font-medium mb-2">Mode</label>
                <select name="mode" class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                    <option value="">All Modes</option>
                    <option value="individual" {{ request('mode') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="group" {{ request('mode') == 'group' ? 'selected' : '' }}>Group</option>
                </select>
            </div>
            <div>
                <label class="block text-pink-500 text-sm font-medium mb-2">Status</label>
                <select name="status" class="w-full bg-[#2A2A32] border border-pink-500/20 rounded-lg px-4 py-2 text-white focus:border-pink-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
            </div>
        </form>
        <div class="mt-4 flex gap-2">
            <button type="submit" form="filter-form" class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Apply Filters
            </button>
            <a href="{{ route('games.index') }}" class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                Clear Filters
            </a>
        </div>
    </div>

    <!-- Create Game Button -->
    <div class="mb-6">
        <a href="{{ route('games.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create New Game
        </a>
    </div>

    <!-- Games Table -->
    @if($games->count() > 0)
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-pink-500/20">
                        <th class="py-3 px-4 text-gray-400 font-medium">Game Name</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Classroom</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Exercise</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Mode</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Type</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Status</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Created By</th>
                        <th class="py-3 px-4 text-gray-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                    <tr class="border-b border-pink-500/20 hover:bg-pink-500/5">
                        <td class="py-4 px-4 text-white font-medium">{{ $game->name }}</td>
                        <td class="py-4 px-4 text-gray-300">{{ $game->classroom->name }}</td>
                        <td class="py-4 px-4 text-gray-300">{{ $game->exercise->title }}</td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-pink-500/20 text-pink-400">
                                {{ ucfirst($game->mode) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                                {{ ucfirst($game->type) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($game->status === 'ongoing') bg-green-500/20 text-green-400
                                @elseif($game->status === 'finished') bg-purple-500/20 text-purple-400
                                @else bg-yellow-500/20 text-yellow-400 @endif">
                                {{ ucfirst($game->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-300">{{ $game->creator->name }}</td>
                        <td class="py-4 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('games.edit', $game) }}" 
                                   class="text-pink-500 hover:text-pink-400 text-sm">Edit</a>
                                <a href="{{ route('games.history', $game) }}" 
                                   class="text-blue-500 hover:text-blue-400 text-sm">History</a>
                                <form method="POST" action="{{ route('games.destroy', $game) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" 
                                            class="text-red-500 hover:text-red-400 text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $games->links() }}
    </div>
    @else
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center">
        <div class="mb-4">
            <i class="fi fi-rr-gamepad text-pink-500 text-5xl"></i>
        </div>
        <h3 class="text-white text-xl font-semibold mb-2">No Games Created Yet</h3>
        <p class="text-gray-400 mb-4">Start by creating your first educational game</p>
        <a href="{{ route('games.create') }}" 
           class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create First Game
        </a>
    </div>
    @endif
</div>
@endsection 