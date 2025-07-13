@extends('classroom.show')

@section('content')
<div class="mt-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white">Waiting Room: {{ $game->name }}</h2>
        <p class="text-gray-400 text-lg mt-2">{{ $game->exercise->title }}</p>
        <div class="flex gap-2 mt-2">
            <span class="px-2 py-1 text-xs rounded-full bg-pink-500/20 text-pink-400">
                {{ ucfirst($game->mode) }}
            </span>
            <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                {{ ucfirst($game->type) }}
            </span>
        </div>
    </div>

    <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Participants</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($game->players as $player)
                <div class="p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20">
                    <div class="text-center">
                        <div class="text-white font-medium mb-2">{{ $player->student_name ?? $player->user->name ?? 'Unknown' }}</div>
                        <div class="text-gray-400 text-sm">Student</div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-400">No participants yet.</div>
            @endforelse
        </div>
    </div>

    @if(auth()->user()->role === 'teacher')
    <div class="flex justify-center mt-6">
        <button type="button" id="startGameBtn" class="px-8 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity text-lg font-semibold">
            Start the Game
        </button>
    </div>
    @else
    <div class="flex justify-center mt-6">
        <div class="px-8 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg text-lg font-semibold opacity-80 cursor-not-allowed">
            Waiting for teacher to start the game...
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->role !== 'teacher')
<script>
// Polling status game setiap 2 detik
setInterval(function() {
    fetch("{{ route('games.status', $game) }}")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ongoing') {
                window.location.href = "{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}";
            }
        });
}, 2000);
</script>
@endif

@if(auth()->user()->role === 'teacher')
<script>
document.getElementById('startGameBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Starting...';
    fetch("{{ route('games.startGame', $game) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "{{ route('classroom.games.play', ['className' => $classroom->name, 'game' => $game->id]) }}";
        } else {
            alert(data.message || 'Failed to start game');
            btn.disabled = false;
            btn.textContent = 'Start the Game';
        }
    })
    .catch(() => {
        alert('Failed to start game');
        btn.disabled = false;
        btn.textContent = 'Start the Game';
    });
});
</script>
@endif
@endsection 