@extends('classroom.show')

@section('content')
<div class="mt-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white">{{ $game->name }}</h2>
        <p class="text-gray-400 text-lg mt-2">Playing: {{ $game->exercise->title }}</p>
        <div class="flex gap-2 mt-2">
            <span class="px-2 py-1 text-xs rounded-full bg-pink-500/20 text-pink-400">
                {{ ucfirst($game->mode) }}
            </span>
            <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                {{ ucfirst($game->type) }}
            </span>
        </div>
    </div>

    @if($game->type === 'offline')
        @if(auth()->user()->role === 'student')
            <!-- Student View for Offline Mode - Read Only -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fi fi-rr-eye text-pink-500 text-5xl"></i>
                    </div>
                    <h3 class="text-white text-xl font-semibold mb-2">View Only Mode</h3>
                    <p class="text-gray-400">This is an offline game. Only teachers can control the game.</p>
                </div>
            </div>

            <!-- Game Info (Read Only) -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-500" id="questionNumber">1</div>
                        <div class="text-gray-400 text-sm">Question</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-500" id="totalQuestions">{{ $game->exercise->questions->count() }}</div>
                        <div class="text-gray-400 text-sm">Total Questions</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-500" id="currentScore">0</div>
                        <div class="text-gray-400 text-sm">Current Score</div>
                    </div>
                </div>
            </div>

            <!-- Question Display (Read Only) -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div id="questionContainer">
                    <h3 class="text-xl font-semibold text-white mb-4" id="questionText">Waiting for teacher to start...</h3>
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Options will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Players/Teams List (Read Only) -->
            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Players/Teams</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="playersContainer">
                    <!-- Players/Teams will be loaded here -->
                </div>
            </div>

        @else
            <!-- Teacher View for Offline Mode - Full Control -->
            <!-- Game Info -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-pink-500" id="timer">30</div>
                        <div class="text-gray-400 text-sm">Seconds Left</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-500" id="questionNumber">1</div>
                        <div class="text-gray-400 text-sm">Question</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-500" id="totalQuestions">{{ $game->exercise->questions->count() }}</div>
                        <div class="text-gray-400 text-sm">Total Questions</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-500" id="currentScore">0</div>
                        <div class="text-gray-400 text-sm">Current Score</div>
                    </div>
                </div>
            </div>

            <!-- Question Display -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div id="questionContainer">
                    <h3 class="text-xl font-semibold text-white mb-4" id="questionText">Loading question...</h3>
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Options will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Players/Teams for Scoring -->
            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Score Players/Teams</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="playersContainer">
                    <!-- Players/Teams will be loaded here -->
                </div>
            </div>

            <!-- Game Controls -->
            <div class="mt-6 flex gap-4">
                <button onclick="nextQuestion()" id="nextButton" 
                        class="px-6 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition-opacity" style="display: none;">
                    Next Question
                </button>
                <button onclick="finishGame()" id="finishButton" 
                        class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:opacity-90 transition-opacity" style="display: none;">
                    Finish Game
                </button>
            </div>
        @endif

    @else
        <!-- Online Mode - Students can participate -->
        @if(auth()->user()->role === 'student')
            <!-- Student View for Online Mode - Can Participate -->
            <!-- Game Info -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-pink-500" id="timer">30</div>
                        <div class="text-gray-400 text-sm">Seconds Left</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-500" id="questionNumber">1</div>
                        <div class="text-gray-400 text-sm">Question</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-500" id="totalQuestions">{{ $game->exercise->questions->count() }}</div>
                        <div class="text-gray-400 text-sm">Total Questions</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-500" id="currentScore">0</div>
                        <div class="text-gray-400 text-sm">Your Score</div>
                    </div>
                </div>
            </div>

            <!-- Question Display -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div id="questionContainer">
                    <h3 class="text-xl font-semibold text-white mb-4" id="questionText">Loading question...</h3>
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Options will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Students in Game</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="studentsContainer">
                    <!-- Students will be loaded here -->
                </div>
            </div>

        @else
            <!-- Teacher View for Online Mode - Can Monitor -->
            <!-- Game Info -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-pink-500" id="timer">30</div>
                        <div class="text-gray-400 text-sm">Seconds Left</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-500" id="questionNumber">1</div>
                        <div class="text-gray-400 text-sm">Question</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-500" id="totalQuestions">{{ $game->exercise->questions->count() }}</div>
                        <div class="text-gray-400 text-sm">Total Questions</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-500" id="currentScore">0</div>
                        <div class="text-gray-400 text-sm">Current Score</div>
                    </div>
                </div>
            </div>

            <!-- Question Display -->
            <div class="bg-[#211F27] rounded-lg p-6 mb-6 border border-pink-500/20">
                <div id="questionContainer">
                    <h3 class="text-xl font-semibold text-white mb-4" id="questionText">Loading question...</h3>
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Options will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="bg-[#211F27] rounded-lg p-6 border border-pink-500/20">
                <h3 class="text-lg font-semibold text-white mb-4">Students in Game</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="studentsContainer">
                    <!-- Students will be loaded here -->
                </div>
            </div>

            <!-- Game Controls -->
            <div class="mt-6 flex gap-4">
                <button onclick="nextQuestion()" id="nextButton" 
                        class="px-6 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition-opacity" style="display: none;">
                    Next Question
                </button>
                <button onclick="finishGame()" id="finishButton" 
                        class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:opacity-90 transition-opacity" style="display: none;">
                    Finish Game
                </button>
            </div>
        @endif
    @endif
</div>

<script>
let currentQuestionIndex = 0;
let questions = @json($game->exercise->questions);
let players = @json($game->players);
let scores = {};
let timer;
let timeLeft = 30;
let gameType = '{{ $game->type }}';
let userRole = '{{ auth()->user()->role }}';

// Initialize scores
players.forEach(player => {
    scores[player.id] = 0;
});

function startGame() {
    if (gameType === 'offline' && userRole === 'student') {
        loadQuestionReadOnly();
    } else {
        loadQuestion();
        startTimer();
    }
}

function loadQuestion() {
    if (currentQuestionIndex >= questions.length) {
        finishGame();
        return;
    }

    const question = questions[currentQuestionIndex];
    document.getElementById('questionNumber').textContent = currentQuestionIndex + 1;
    document.getElementById('questionText').textContent = question.question_text;
    
    const optionsContainer = document.getElementById('optionsContainer');
    optionsContainer.innerHTML = '';
    
    if (question.options) {
        question.options.forEach((option, index) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'p-3 bg-[#2A2A32] rounded-lg border border-pink-500/20 hover:border-pink-500/40 transition-all cursor-pointer';
            optionDiv.innerHTML = `
                <div class="flex items-center">
                    <span class="w-6 h-6 rounded-full bg-pink-500/20 text-pink-400 text-sm font-medium mr-3 flex items-center justify-center">
                        ${String.fromCharCode(65 + index)}
                    </span>
                    <span class="text-white">${option}</span>
                </div>
            `;
            optionDiv.onclick = () => selectAnswer(index);
            optionsContainer.appendChild(optionDiv);
        });
    }
}

function loadQuestionReadOnly() {
    if (currentQuestionIndex >= questions.length) {
        return;
    }

    const question = questions[currentQuestionIndex];
    document.getElementById('questionNumber').textContent = currentQuestionIndex + 1;
    document.getElementById('questionText').textContent = question.question_text;
    
    const optionsContainer = document.getElementById('optionsContainer');
    optionsContainer.innerHTML = '';
    
    if (question.options) {
        question.options.forEach((option, index) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'p-3 bg-[#2A2A32] rounded-lg border border-pink-500/20';
            optionDiv.innerHTML = `
                <div class="flex items-center">
                    <span class="w-6 h-6 rounded-full bg-pink-500/20 text-pink-400 text-sm font-medium mr-3 flex items-center justify-center">
                        ${String.fromCharCode(65 + index)}
                    </span>
                    <span class="text-white">${option}</span>
                </div>
            `;
            optionsContainer.appendChild(optionDiv);
        });
    }
}

function selectAnswer(selectedIndex) {
    const question = questions[currentQuestionIndex];
    const isCorrect = selectedIndex === question.correct_answer;
    
    // Calculate score based on time left
    let points = 0;
    if (isCorrect) {
        points = Math.max(1, Math.floor(timeLeft / 3));
    }
    
    // Highlight correct answer
    const options = document.querySelectorAll('#optionsContainer > div');
    options.forEach((option, index) => {
        if (index === question.correct_answer) {
            option.classList.add('border-green-500', 'bg-green-500/20');
        } else if (index === selectedIndex && !isCorrect) {
            option.classList.add('border-red-500', 'bg-red-500/20');
        }
    });
    
    // Show result
    const resultDiv = document.createElement('div');
    resultDiv.className = `mt-4 p-4 rounded-lg ${isCorrect ? 'bg-green-500/20 border border-green-500' : 'bg-red-500/20 border border-red-500'}`;
    resultDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fi ${isCorrect ? 'fi-rr-check text-green-400' : 'fi-rr-cross text-red-400'} text-xl mr-2"></i>
            <span class="text-white font-medium">${isCorrect ? 'Correct!' : 'Incorrect!'}</span>
            ${isCorrect ? `<span class="ml-2 text-green-400">+${points} points</span>` : ''}
        </div>
    `;
    document.getElementById('questionContainer').appendChild(resultDiv);
    
    // Stop timer
    clearInterval(timer);
    
    // If student in online mode, send score
    if (gameType === 'online' && userRole === 'student') {
        sendStudentScore(points);
    }
    
    // Show next/finish button for teachers
    if (userRole !== 'student') {
        if (currentQuestionIndex < questions.length - 1) {
            document.getElementById('nextButton').style.display = 'block';
        } else {
            document.getElementById('finishButton').style.display = 'block';
        }
    }
}

function sendStudentScore(points) {
    fetch('{{ route("classroom.games.score", ["className" => $classroom->name, "game" => $game->id]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            player_id: {{ auth()->user()->id }},
            score: points,
            question_index: currentQuestionIndex
        })
    });
}

function nextQuestion() {
    currentQuestionIndex++;
    timeLeft = 30;
    document.getElementById('timer').textContent = timeLeft;
    document.getElementById('nextButton').style.display = 'none';
    document.getElementById('finishButton').style.display = 'none';
    
    // Remove result message
    const resultDiv = document.querySelector('#questionContainer > div:last-child');
    if (resultDiv && resultDiv.classList.contains('mt-4')) {
        resultDiv.remove();
    }
    
    if (gameType === 'offline' && userRole === 'student') {
        loadQuestionReadOnly();
    } else {
        loadQuestion();
        startTimer();
    }
}

function startTimer() {
    clearInterval(timer);
    timer = setInterval(() => {
        timeLeft--;
        document.getElementById('timer').textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            selectAnswer(-1);
        }
    }, 1000);
}

function scorePlayer(playerId, points) {
    scores[playerId] += points;
    document.getElementById('currentScore').textContent = points;
    
    // Update player score display
    const playerButton = document.querySelector(`[data-player-id="${playerId}"]`);
    if (playerButton) {
        const scoreSpan = playerButton.querySelector('.score');
        scoreSpan.textContent = scores[playerId];
    }
    
    // Send score to server
    fetch('{{ route("classroom.games.score", ["className" => $classroom->name, "game" => $game->id]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            player_id: playerId,
            score: points
        })
    });
}

function finishGame() {
    clearInterval(timer);
    window.location.href = '{{ route("classroom.games.scoreboard", ["className" => $classroom->name, "game" => $game->id]) }}';
}

// Load players/teams
function loadPlayers() {
    const container = document.getElementById('playersContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    players.forEach(player => {
        const playerDiv = document.createElement('div');
        playerDiv.className = 'p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20 hover:border-pink-500/40 transition-all cursor-pointer';
        playerDiv.setAttribute('data-player-id', player.id);
        
        if (userRole !== 'student') {
            playerDiv.onclick = () => scorePlayer(player.id, 10);
        }
        
        const displayName = player.team ? player.team.name : (player.student_name || player.user?.name || 'Unknown');
        
        playerDiv.innerHTML = `
            <div class="text-center">
                <div class="text-white font-medium mb-2">${displayName}</div>
                <div class="text-gray-400 text-sm mb-2">Score: <span class="score text-pink-400">${scores[player.id]}</span></div>
                ${userRole !== 'student' ? '<button class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600 transition-colors">+10 Points</button>' : ''}
            </div>
        `;
        
        container.appendChild(playerDiv);
    });
}

// Load students for online mode
function loadStudents() {
    const container = document.getElementById('studentsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    // Get students from classroom
    const students = @json($classroom->users()->where('role', 'student')->get());
    
    students.forEach(student => {
        const studentDiv = document.createElement('div');
        studentDiv.className = 'p-4 bg-[#2A2A32] rounded-lg border border-pink-500/20';
        studentDiv.innerHTML = `
            <div class="text-center">
                <div class="text-white font-medium mb-2">${student.name}</div>
                <div class="text-gray-400 text-sm">Student</div>
            </div>
        `;
        
        container.appendChild(studentDiv);
    });
}

// Initialize game
document.addEventListener('DOMContentLoaded', function() {
    if (gameType === 'offline') {
        if (userRole === 'student') {
            loadPlayers();
            loadQuestionReadOnly();
        } else {
            loadPlayers();
            startGame();
        }
    } else {
        loadStudents();
        startGame();
    }
});
</script>
@endsection 