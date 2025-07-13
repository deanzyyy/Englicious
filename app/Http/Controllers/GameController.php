<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameTeam;
use App\Models\GamePlayer;
use App\Models\GameScore;
use App\Models\Classroom;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // Game Index (Admin/Teacher only)
    public function index(Request $request)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Game::with(['classroom', 'exercise', 'creator']);

        // Filter by classroom
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }

        // Filter by mode
        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $games = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Filter classrooms by teacher for teacher role
        if (auth()->user()->role === 'teacher') {
            $classrooms = Classroom::where('teacher_id', auth()->id())->orderBy('name')->get();
        } else {
            $classrooms = Classroom::orderBy('name')->get();
        }

        return view('games.index', compact('games', 'classrooms'));
    }

    // Leaderboard (Admin/Teacher only)
    public function leaderboard()
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $topPlayers = GameScore::with(['player.user', 'player.team', 'game.classroom'])
            ->select('player_id', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(DISTINCT game_id) as games_played'))
            ->groupBy('player_id')
            ->orderBy('total_score', 'desc')
            ->limit(5)
            ->get();

        return view('games.leaderboard', compact('topPlayers'));
    }

    // Create Game Form (Admin/Teacher only)
    public function create()
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        // Filter classrooms by teacher for teacher role
        if (auth()->user()->role === 'teacher') {
            $classrooms = Classroom::where('teacher_id', auth()->id())->orderBy('name')->get();
        } else {
            $classrooms = Classroom::orderBy('name')->get();
        }

        // Filter exercises by teacher for teacher role
        if (auth()->user()->role === 'teacher') {
            $exercises = Exercise::where('created_by', auth()->id())
                ->whereNull('file_path')
                ->with('questions')
                ->get();
        } else {
            $exercises = Exercise::whereNull('file_path')->with('questions')->get();
        }

        return view('games.create', compact('classrooms', 'exercises'));
    }

    // Store Game (Admin/Teacher only)
    public function store(Request $request)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'exercise_id' => 'required|exists:exercises,id',
            'name' => 'required|string|max:255',
            'mode' => 'required|in:individual,group',
            'type' => 'required|in:online,offline',
            'players' => 'required_if:type,offline|array|min:1',
            'players.*.name' => 'required_if:type,offline|string|max:255',
            'players.*.members' => 'nullable|array',
            'players.*.members.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $game = Game::create([
                'classroom_id' => $validated['classroom_id'],
                'exercise_id' => $validated['exercise_id'],
                'name' => $validated['name'],
                'mode' => $validated['mode'],
                'type' => $validated['type'],
                'status' => 'draft',
                'created_by' => Auth::id()
            ]);

            // Only create teams and players for offline mode
            if ($validated['type'] === 'offline' && isset($validated['players'])) {
                foreach ($validated['players'] as $playerData) {
                    if ($validated['mode'] === 'group') {
                        $team = GameTeam::create([
                            'game_id' => $game->id,
                            'name' => $playerData['name']
                        ]);

                        // Add team members
                        if (isset($playerData['members'])) {
                            foreach ($playerData['members'] as $memberName) {
                                if (!empty($memberName)) {
                                    GamePlayer::create([
                                        'game_id' => $game->id,
                                        'team_id' => $team->id,
                                        'student_name' => $memberName
                                    ]);
                                }
                            }
                        }
                    } else {
                        // Individual mode
                        GamePlayer::create([
                            'game_id' => $game->id,
                            'student_name' => $playerData['name']
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('games.index')
                ->with('success', 'Game created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create game: ' . $e->getMessage()]);
        }
    }

    // Edit Game (Admin/Teacher only)
    public function edit(Game $game)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        // Filter classrooms by teacher for teacher role
        if (auth()->user()->role === 'teacher') {
            $classrooms = Classroom::where('teacher_id', auth()->id())->orderBy('name')->get();
        } else {
            $classrooms = Classroom::orderBy('name')->get();
        }

        // Filter exercises by teacher for teacher role
        if (auth()->user()->role === 'teacher') {
            $exercises = Exercise::where('created_by', auth()->id())
                ->whereNull('file_path')
                ->with('questions')
                ->get();
        } else {
            $exercises = Exercise::whereNull('file_path')->with('questions')->get();
        }

        return view('games.edit', compact('game', 'classrooms', 'exercises'));
    }

    // Update Game (Admin/Teacher only)
    public function update(Request $request, Game $game)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'exercise_id' => 'required|exists:exercises,id',
            'name' => 'required|string|max:255',
            'mode' => 'required|in:individual,group',
            'type' => 'required|in:online,offline'
        ]);

        $game->update($validated);

        return redirect()->route('games.index')
            ->with('success', 'Game updated successfully!');
    }

    // Delete Game (Admin/Teacher only)
    public function destroy(Game $game)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $game->delete();

        return redirect()->route('games.index')
            ->with('success', 'Game deleted successfully!');
    }

    // Game History (Admin/Teacher only)
    public function history(Game $game)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized action.');
        }

        $game->load(['scores.player', 'scores.player.team', 'scores.player.user']);

        return view('games.history', compact('game'));
    }

    // Classroom Games Tab (All roles)
    public function classroomGames($className)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $games = Game::where('classroom_id', $classroom->id)
            ->with(['exercise', 'creator', 'players'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('classroom.games.index', compact('classroom', 'games'));
    }

    // Play Game (All roles)
    public function play($className, Game $game)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        
        if ($game->classroom_id !== $classroom->id) {
            abort(404);
        }

        // For online mode, ensure current student is a player (participant), baik di waiting room maupun gameplay
        if ($game->type === 'online' && auth()->user()->role === 'student') {
            $player = GamePlayer::firstOrCreate([
                'game_id' => $game->id,
                'user_id' => auth()->id()
            ], [
                'student_name' => auth()->user()->name
            ]);
        }

        // Load game data
        $game->load(['exercise.questions' => function($q) {
            $q->where('type', 'optional')->orWhere('type', 'multiple_choice');
        }, 'players.team', 'players.user', 'scores']);

        // Jika game online dan status draft, arahkan ke waiting room (pragame)
        if ($game->type === 'online' && $game->status === 'draft') {
            return view('classroom.games.pragame', compact('classroom', 'game'));
        }

        return view('classroom.games.play', compact('classroom', 'game'));
    }

    // Score Game (All roles)
    public function score(Request $request, $className, Game $game)
    {
        $validated = $request->validate([
            'player_id' => 'required',
            'score' => 'required|integer|min:0',
            'question_index' => 'nullable|integer|min:0'
        ]);

        // Handle online mode where students answer directly
        if ($game->type === 'online' && auth()->user()->role === 'student') {
            // Find or create player for current user
            $player = GamePlayer::firstOrCreate([
                'game_id' => $game->id,
                'user_id' => auth()->id()
            ], [
                'student_name' => auth()->user()->name
            ]);
            
            $validated['player_id'] = $player->id;
        } else {
            // Offline mode - validate player exists
            $player = GamePlayer::findOrFail($validated['player_id']);
            
            if ($player->game_id !== $game->id) {
                return response()->json(['error' => 'Invalid player'], 400);
            }
        }

        // Update or create score
        GameScore::updateOrCreate(
            ['game_id' => $game->id, 'player_id' => $validated['player_id']],
            ['score' => DB::raw('score + ' . $validated['score'])]
        );

        return response()->json(['success' => true]);
    }

    // Scoreboard (All roles)
    public function scoreboard($className, Game $game)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        
        if ($game->classroom_id !== $classroom->id) {
            abort(404);
        }

        $scores = GameScore::where('game_id', $game->id)
            ->with(['player.team', 'player.user'])
            ->orderBy('score', 'desc')
            ->get();

        return view('classroom.games.scoreboard', compact('classroom', 'game', 'scores'));
    }

    // Start Game (Admin/Teacher only)
    public function startGame(Game $game)
    {
        // Check if user has admin or teacher role
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'teacher'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Only allow if status is draft and at least 1 participant
        if ($game->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Game is already started or finished.']);
        }
        if ($game->players()->count() < 1) {
            return response()->json(['success' => false, 'message' => 'No participants have joined yet.']);
        }

        try {
            $game->update(['status' => 'ongoing']);
            return response()->json([
                'success' => true,
                'message' => 'Game started successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start game: ' . $e->getMessage()
            ], 500);
        }
    }

    // API: Get game status (for waiting room polling)
    public function status(Game $game)
    {
        return response()->json([
            'status' => $game->status
        ]);
    }
} 