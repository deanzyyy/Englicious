@extends('layouts.app')

@section('content')
<div class="ml-64 p-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Game Leaderboard</h1>
        <p class="text-gray-400">Top performers across all games</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaderboard -->
        <div class="lg:col-span-2">
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-white mb-6">Top 5 Players</h2>
                    
                    @if($topPlayers->count() > 0)
                    <div class="space-y-4">
                        @foreach($topPlayers as $index => $player)
                        <div class="flex items-center justify-between p-4 rounded-lg {{ ($index === 0) ? 'bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/40' : 'bg-[#2A2A32] border border-pink-500/20' }} hover:border-pink-500/40 transition-all">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 {{ ($index === 0) ? 'bg-yellow-500' : (($index === 1) ? 'bg-gray-400' : (($index === 2) ? 'bg-orange-600' : 'bg-pink-500')) }}">
                                    @if($index === 0)
                                        <i class="fi fi-rr-trophy text-white text-xl"></i>
                                    @else
                                        <span class="text-white font-bold text-lg">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-white font-semibold">
                                        {{ ($player->player->team) ? $player->player->team->name : ($player->player->student_name ?? $player->player->user->name ?? 'Unknown') }}
                                    </div>
                                    <div class="text-gray-400 text-sm">
                                        {{ $player->games_played }} games played
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-pink-400">{{ $player->total_score }}</div>
                                <div class="text-gray-400 text-sm">total points</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <i class="fi fi-rr-trophy text-pink-500 text-5xl"></i>
                        </div>
                        <h3 class="text-white text-xl font-semibold mb-2">No Scores Yet</h3>
                        <p class="text-gray-400">No games have been completed yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="space-y-6">
            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Games</span>
                        <span class="text-white font-semibold">{{ \App\Models\Game::where('status', 'finished')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Active Games</span>
                        <span class="text-white font-semibold">{{ \App\Models\Game::where('status', 'ongoing')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Players</span>
                        <span class="text-white font-semibold">{{ \App\Models\GamePlayer::count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Teams</span>
                        <span class="text-white font-semibold">{{ \App\Models\GameTeam::count() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('games.index') }}" 
                       class="block w-full px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity text-center">
                        View All Games
                    </a>
                    <a href="{{ route('games.create') }}" 
                       class="block w-full px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all text-center">
                        Create New Game
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 