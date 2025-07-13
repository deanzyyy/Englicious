<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\FilePreviewController;
use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\LibreChatController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherRegistrationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DictionaryController;

// Landing page as default
Route::get('/', function () {
    return view('landing', ['title' => 'landing']);
});

// (Optional) Keep /landing route for direct access
Route::get('/landing', function () {
    return view('landing', ['title' => 'landing']);
});

// Test route for debugging search (public)
Route::get('/test-search', [ClassroomController::class, 'testSearch']);

// Route to create test data
Route::get('/create-test-data', [ClassroomController::class, 'createTestData']);

// Route to show database data
Route::get('/show-database-data', [ClassroomController::class, 'showDatabaseData']);

// Route to create simple test data
Route::get('/create-simple-test-data', [ClassroomController::class, 'createSimpleTestData']);

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Forgot Password Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Forgot Password Manual (tanpa email)
Route::post('/forgot-password/manual', [App\Http\Controllers\Auth\ForgotPasswordManualController::class, 'checkUser'])->name('password.manual.check');
Route::get('/reset-password/manual', [App\Http\Controllers\Auth\ForgotPasswordManualController::class, 'showResetForm'])->name('password.manual.form');
Route::post('/reset-password/manual', [App\Http\Controllers\Auth\ForgotPasswordManualController::class, 'reset'])->name('password.manual.reset');

// Semua route lain diamankan dengan middleware 'auth'
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Teacher Registration (Simple, Admin Only)
    Route::middleware(['auth'])->group(function () {
        Route::get('/teacher-registration', [TeacherRegistrationController::class, 'showForm'])->name('teacher.registration.simple.form');
        Route::post('/teacher-registration', [TeacherRegistrationController::class, 'register'])->name('teacher.registration.simple.register');
    });

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Add new route for leaving a classroom
    Route::post('/classroom/{className}/leave', [ClassroomController::class, 'leaveClassroom'])->name('classroom.leave');

    // Home/Dashboard
    Route::get('/', [ClassroomController::class, 'index'])->name('home');

    // Classroom main listing page
    Route::get('/classroom', [ClassroomController::class, 'classroomList'])->name('classroom.list');

    // Exercise Routes
    Route::get('/exercise', function(){
        return view('exercise', ['title' => 'exercise']);
    });

    Route::get('/materials', function(){
        return view('materials.materialsGeneral');
    });

    // Classroom Routes
    Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
    Route::post('/classroom/verify-password/{className}', [ClassroomController::class, 'verifyPassword'])
        ->name('classroom.verify-password')
        ->where('className', '.*');
    Route::put('/classroom/{className}/password', [ClassroomController::class, 'updatePassword'])->name('classroom.update-password')->where('className', '.*');
    Route::delete('/classroom/delete/{id}', [ClassroomController::class, 'destroy'])->name('classroom.destroy');
    Route::get('/classroom/{className}', [ClassroomController::class, 'show'])->name('classroom.show');
    Route::get('/classroom/{className}/materials', [ClassroomController::class, 'materials'])->name('classroom.materials');
    Route::get('/classroom/{className}/exercises', [ClassroomController::class, 'exercises'])->name('classroom.exercises');
    Route::delete('/classroom/{classroom}/exercises/{exercise}', [ClassroomController::class, 'removeExercise'])->name('classroom.exercise.remove');
    Route::get('/classroom/{className}/presence', [ClassroomController::class, 'presence'])->name('classroom.presence');
    Route::get('/classroom/{className}/grades', [GradeController::class, 'index'])->name('classroom.grades');
    Route::get('/classroom/{className}/grades/student/{studentId}', [GradeController::class, 'getStudentDetails'])->name('classroom.grades.student');
    Route::post('/classroom/{className}/grades/assignment/{submissionId}', [GradeController::class, 'updateAssignmentGrade'])->name('classroom.grades.assignment');
    Route::get('/classroom/{className}/students', [ClassroomController::class, 'students'])->name('classroom.students');
    Route::post('/classroom/{className}/students', [ClassroomController::class, 'addStudent'])->name('classroom.students.add');
    Route::put('/classroom/{className}/students/{studentId}', [ClassroomController::class, 'updateStudent'])->name('classroom.students.update');
    Route::delete('/classroom/{className}/students/{studentId}', [ClassroomController::class, 'removeStudent'])->name('classroom.students.remove');

    // Exercise Management Routes
    Route::prefix('exercises')->name('exercises.')->group(function () {
        Route::get('/', [ExerciseController::class, 'index'])->name('index');
        Route::get('/create', [ExerciseController::class, 'create'])->name('create');
        Route::post('/', [ExerciseController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ExerciseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExerciseController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExerciseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/details', [ExerciseController::class, 'details'])->name('details');
        Route::get('/{id}/take', [ExerciseController::class, 'take'])->name('take');
        Route::post('/{id}/submit', [ExerciseController::class, 'submit'])->name('submit');
        Route::post('/{id}/send-to-class', [ExerciseController::class, 'sendToClass'])->name('send-to-class');
        Route::delete('/classroom/{classroomId}/exercise/{exerciseId}', [ExerciseController::class, 'removeFromClass'])->name('remove-from-class');
        Route::get('/classroom/{classroomId}/exercises', [ExerciseController::class, 'getClassroomExercises'])->name('classroom-exercises');
        Route::delete('/classroom/{classroom}/remove/{exercise}', [ExerciseController::class, 'removeFromClassroom'])->name('remove-from-classroom');
    });

    // Topic Management Routes
    Route::post('/topics', [TopicController::class, 'storeTopic'])->name('topics.store');
    Route::post('/subtopics', [TopicController::class, 'storeSubtopic'])->name('subtopics.store');
    Route::get('/api/topics/get-id/{name}', [TopicController::class, 'getTopicId']);
    Route::get('/get-subtopics/{topicId}', [ExerciseController::class, 'getSubtopics'])->name('get-subtopics');
    Route::get('/topics-by-category', [TopicController::class, 'getTopicsByCategory']);
    Route::get('/subtopics-by-topic', [TopicController::class, 'getSubtopicsByTopic']);

    // Attendance Routes (Independent)
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/{classroom}', [AttendanceController::class, 'show'])->name('show');
        Route::post('/update', [AttendanceController::class, 'updateAttendance'])->name('update');
        Route::get('/{classroom}/history', [AttendanceController::class, 'history'])->name('history');
        Route::get('/{classroom}/export-pdf', [AttendanceController::class, 'exportPdf'])->name('export-pdf');
    });

    // Student Routes
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // File Preview Route
    Route::get('/preview/{exercise}', [FilePreviewController::class, 'preview'])->name('preview.file');

    // Add this with your other routes
    Route::get('/get-classrooms', [ClassroomController::class, 'getClassrooms'])->name('classrooms.list');

    // OpenRouter AI Routes
    Route::get('/ai', [OpenRouterController::class, 'index'])->name('openrouter.chat');
    Route::post('/ai/process', [OpenRouterController::class, 'chat'])->name('openrouter.process');

    // LibreChat Routes
    Route::get('/chat', [LibreChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [LibreChatController::class, 'chat'])->name('chat.message');

    // Exercise Result Route
    Route::get('/exercises/{id}/result', [ExerciseController::class, 'result'])->name('exercises.result');

    // Classroom search route
    Route::get('/search-classroom', [ClassroomController::class, 'searchClassroom']);

    // Global search route for classrooms, exercises, and materials
    Route::get('/global-search', [ClassroomController::class, 'globalSearch']);

    // New routes for classroom exercises
    Route::prefix('classroom/{className}')->name('classroom.')->group(function () {
        Route::get('materials', [ClassroomController::class, 'materials'])->name('materials');
        Route::get('exercises', [ClassroomController::class, 'exercises'])->name('exercises');
        Route::get('assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submitAssignment'])->name('assignments.submit');
        Route::get('exercises/{exerciseId}/take', [ExerciseController::class, 'take'])->name('exercise.take');
        Route::post('exercises/{exerciseId}/submit', [ExerciseController::class, 'submit'])->name('exercise.submit');
        Route::get('exercises/{exerciseId}/result', [ClassroomController::class, 'exerciseResult'])->name('exercise.result');
        Route::post('assignments/{assignment}/submissions/{submission}/grade', [AssignmentController::class, 'updateSubmissionGrade'])->name('assignments.submissions.grade');
        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::get('exercises/{exerciseId}/review', [ExerciseController::class, 'reviewAnswers'])->name('exercise.review');
        Route::post('exercises/{exerciseId}/review/submit', [ExerciseController::class, 'submitReview'])->name('exercise.review.submit');
        Route::get('exercises/{exerciseId}/review/export', [ExerciseController::class, 'exportReview'])->name('exercise.review.export');
    });

    // Question routes
    Route::post('/questions/{question}/delete-image', [QuestionController::class, 'deleteImage'])->name('questions.delete-image');
    Route::post('/questions/{question}/delete-audio', [QuestionController::class, 'deleteAudio'])->name('questions.delete-audio');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Material Management Routes
    Route::prefix('materials')->name('materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('index');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [MaterialController::class, 'show'])->name('show');
        Route::get('/{id}/content', [MaterialController::class, 'getContent'])->name('content');
        Route::post('/{id}/send-to-class', [MaterialController::class, 'sendToClass'])->name('send-to-class');
        Route::delete('/classroom/{classroomId}/material/{materialId}', [MaterialController::class, 'removeFromClass'])->name('remove-from-class');
    });
    // Tambahkan endpoint JSON untuk detail material
    Route::get('/materials/{id}', [MaterialController::class, 'showJson']);

    // Tambahkan route POST untuk send-to-class agar sesuai dengan request JS
    Route::post('/materials/send-to-class', [MaterialController::class, 'sendToClass']);

    // Tambahkan route DELETE untuk hapus material dari classroom agar bisa diakses dari JS
    Route::delete('/materials/classroom/{classroomId}/material/{materialId}', [MaterialController::class, 'removeFromClass']);

    // Route untuk exercise umum (tanpa classroom)
    Route::get('/exercises/{id}/take', [ExerciseController::class, 'takeGeneral'])->name('exercises.take.general');

    // New route for my exercise results
    Route::get('/my-exercise-results', [ExerciseController::class, 'myResults'])->name('exercises.my_results');

    // New route for exercise review
    Route::get('classroom/{className}/exercise/{exerciseId}/review', [ExerciseController::class, 'reviewAnswers'])->name('classroom.exercise.review');
    Route::post('classroom/{className}/exercise/{exerciseId}/review', [ExerciseController::class, 'submitReview'])->name('classroom.exercise.review.submit');

    // Dictionary + Translate
    Route::get('/dictionary', [DictionaryController::class, 'index'])->name('dictionary.index');
    Route::post('/dictionary/search', [DictionaryController::class, 'search'])->name('dictionary.search');

    // Gemini AI Chat Route
    Route::get('/voca', function() {
        return view('gemini.chat');
    })->name('voca.chat');

    // Game Routes (NEW)
    Route::middleware(['auth'])->group(function () {
        Route::get('/games', [GameController::class, 'index'])->name('games.index');
        Route::get('/games/leaderboard', [GameController::class, 'leaderboard'])->name('games.leaderboard');
        Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
        Route::post('/games', [GameController::class, 'store'])->name('games.store');
        Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
        Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
        Route::get('/games/{game}/history', [GameController::class, 'history'])->name('games.history');
        Route::get('/games/{game}/status', [App\Http\Controllers\GameController::class, 'status'])->name('games.status');
    });
    // Classroom Game Tab (semua role)
    Route::get('/classroom/{className}/games', [GameController::class, 'classroomGames'])->name('classroom.games');
    Route::get('/classroom/{className}/games/{game}/play', [GameController::class, 'play'])->name('classroom.games.play');
    Route::post('/classroom/{className}/games/{game}/score', [GameController::class, 'score'])->name('classroom.games.score');
    Route::get('/classroom/{className}/games/{game}/scoreboard', [GameController::class, 'scoreboard'])->name('classroom.games.scoreboard');
    Route::post('/games/{game}/start', [GameController::class, 'startGame'])->name('games.start');
    Route::post('/games/{game}/start', [GameController::class, 'startGame'])->name('games.startGame');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class)->except(['show']);
});

// Endpoint public untuk news (JSON, like, view)
Route::middleware(['auth'])->group(function () {
    Route::get('/news/list', [NewsController::class, 'list']);
    Route::post('/news/{id}/like', [NewsController::class, 'like']);
    Route::post('/news/{id}/increment-view', [NewsController::class, 'incrementView']);
});




