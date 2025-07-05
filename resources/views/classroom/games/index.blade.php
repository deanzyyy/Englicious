@extends('classroom.show')

@section('content')
<div class="mt-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">Classroom Games</h2>
            <p class="text-gray-400 text-lg mt-2">Educational games for {{ $classroom->name }}</p>
        </div>
        @if(auth()->user()->role !== 'student')
        <div>
            <a href="{{ route('games.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create New Game
            </a>
        </div>
        @endif
    </div>

    @if($games->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($games as $game)
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-6 hover:border-pink-500/40 transition-all">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-white mb-2">{{ $game->name }}</h3>
                    <p class="text-gray-400 text-sm">{{ $game->exercise->title }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-pink-500/20 text-pink-400">
                        {{ ucfirst($game->mode) }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                        {{ ucfirst($game->type) }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Status:</span>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($game->status === 'ongoing') bg-green-500/20 text-green-400
                        @elseif($game->status === 'finished') bg-purple-500/20 text-purple-400
                        @else bg-yellow-500/20 text-yellow-400 @endif">
                        {{ ucfirst($game->status) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Created by:</span>
                    <span class="text-white">{{ $game->creator->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Questions:</span>
                    <span class="text-white">{{ $game->exercise->questions_count }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                @if($game->status === 'draft')
                    @if(auth()->user()->role !== 'student')
                        <button onclick="startGame({{ $game->id }})" 
                                class="flex-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition-opacity text-sm">
                            Start Game
                        </button>
                    @endif
                @elseif($game->status === 'ongoing')
                    @if($game->type === 'offline')
                        @if(auth()->user()->role === 'student')
                            <a href="{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}" 
                               class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center text-sm">
                                View Games
                            </a>
                        @else
                            <a href="{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}" 
                               class="flex-1 px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center text-sm">
                                Continue Playing
                            </a>
                        @endif
                    @else
                        @if(auth()->user()->role === 'student')
                            <a href="{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}" 
                               class="flex-1 px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center text-sm">
                                Join the Game
                            </a>
                        @else
                            <a href="{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}" 
                               class="flex-1 px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center text-sm">
                                Continue Playing
                            </a>
                        @endif
                    @endif
                @else
                    <a href="{{ route('classroom.games.scoreboard', ['className' => $classroom->name, 'game' => $game->id]) }}" 
                       class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center text-sm">
                        View Results
                    </a>
                @endif
                
                @if(auth()->user()->role !== 'student')
                <div class="flex gap-1">
                    <a href="{{ route('games.edit', $game) }}" 
                       class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('games.destroy', $game) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" 
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 p-8 text-center">
        <div class="mb-4">
            <i class="fi fi-rr-gamepad text-pink-500 text-5xl"></i>
        </div>
        <h3 class="text-white text-xl font-semibold mb-2">No Games Created Yet</h3>
        <p class="text-gray-400 mb-4">Start by creating an educational game for this classroom</p>
        @if(auth()->user()->role !== 'student')
        <a href="{{ route('games.create') }}" 
           class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create First Game
        </a>
        @endif
    </div>
    @endif
</div>

<script>
function startGame(gameId) {
    if (confirm('Are you sure you want to start this game? This action cannot be undone.')) {
        // Update game status to ongoing
        fetch(`/games/${gameId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Failed to start game: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error starting game: ' + error.message);
        });
    }
}
</script>
@endsection 