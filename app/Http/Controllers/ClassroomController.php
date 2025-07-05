<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\StudentSubmission;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Exercise;
use App\Models\Material;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::latest()->get();
        // Jika student, ambil latest exercise/materials dari classroom yang diikuti
        if (auth()->check() && auth()->user()->role === 'student') {
            $student = auth()->user();
            $classroomIds = $student->classrooms()->pluck('classrooms.id');
            // Ambil exercise yang sudah dikirim ke classroom yang diikuti
            $latestExercises = \App\Models\Exercise::whereHas('classrooms', function($q) use ($classroomIds) {
                $q->whereIn('classrooms.id', $classroomIds);
            })->orderByDesc('created_at')->limit(5)->get();
            // Ambil materials yang sudah dikirim ke classroom yang diikuti
            $latestMaterials = \App\Models\Material::whereHas('classrooms', function($q) use ($classroomIds) {
                $q->whereIn('classrooms.id', $classroomIds);
            })->orderByDesc('created_at')->limit(5)->get();
            return view('Home', compact('classrooms', 'latestExercises', 'latestMaterials'));
        }
        return view('Home', compact('classrooms'));
    }

    public function classroomList()
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            $classrooms = Auth::user()->classrooms()->latest()->get();
        } else if (Auth::check()) {
            // Untuk guru/admin
            $classrooms = Classroom::latest()->get();
        } else {
            // Jika guest, redirect ke login
            return redirect()->route('login');
        }
        return view('classroom.list', compact('classrooms'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input dengan aturan yang lebih ketat
            $validated = $request->validate([
                'class_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('classrooms', 'name')->whereNull('deleted_at')
                ],
                'description' => 'required|string|max:1000',
                'password' => 'required|string|min:4|max:8',
            ], [
                'class_name.unique' => 'Nama kelas sudah digunakan.',
                'class_name.required' => 'Nama kelas wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 4 karakter.',
                'password.max' => 'Password maksimal 8 karakter.',
            ]);

            // Generate unique code
            $code = strtoupper(Str::random(6));
            while (Classroom::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(6));
            }

            // Simpan classroom dengan transaction untuk memastikan data tersimpan dengan benar
            $classroom = DB::transaction(function () use ($validated, $code) {
                $classroom = new Classroom();
                $classroom->name = $validated['class_name'];
                $classroom->description = $validated['description'];
                $classroom->password = $validated['password'];
                $classroom->code = $code;
                $classroom->teacher_id = Auth::id();
                $classroom->save();

                // Log activity
                Log::info('Classroom created', [
                    'classroom_id' => $classroom->id,
                    'teacher_id' => Auth::id(),
                    'name' => $classroom->name
                ]);

                return $classroom;
            });

            return response()->json([
                'success' => true,
                'classroom' => $classroom,
                'message' => 'Classroom berhasil dibuat'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error when creating classroom', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->errors()['class_name'][0] ?? 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating classroom: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->delete();

            return response()->json([
                'success' => true,
                'message' => 'Classroom deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting classroom: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete classroom'
            ], 500);
        }
    }

    public function show($name)
    {
        try {
            $classroom = Classroom::with([
                'students',
                'teacher',
                'exercises' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->where('name', $name)->firstOrFail();

            // Proteksi: teacher hanya bisa akses classroom miliknya
            if (Auth::user()->role === 'teacher' && Auth::id() !== $classroom->teacher_id) {
                throw new \Illuminate\Auth\Access\AuthorizationException('You are not allowed to access this classroom.');
            }
            // Student hanya bisa akses classroom yang diikutinya
            if (Auth::user()->role === 'student' && !$classroom->students->contains('id', Auth::id())) {
                throw new \Illuminate\Auth\Access\AuthorizationException('You are not allowed to access this classroom.');
            }

            $today = Carbon::today();
            $attendances = Attendance::where('classroom_id', $classroom->id)
                ->where('date', $today)
                ->with('user')
                ->get()
                ->keyBy('user_id');

            if ($attendances->isEmpty() && $classroom->students->isNotEmpty()) {
                DB::transaction(function () use ($classroom, $today) {
                    foreach ($classroom->students as $student) {
                        Attendance::create([
                            'user_id' => $student->id,
                            'classroom_id' => $classroom->id,
                            'status' => 'present',
                            'date' => $today
                        ]);
                    }
                });
                $attendances = Attendance::where('classroom_id', $classroom->id)
                    ->where('date', $today)
                    ->with('user')
                    ->get()
                    ->keyBy('user_id');
            }

            $latestExercise = null;
            $todayExercise = null;
            $incompleteExercises = collect();
            if (Auth::user()->role === 'student') {
                $latestExercise = $classroom->exercises->first();
                $todayExercise = $classroom->exercises->where('created_at', '>=', Carbon::today())->first();
                $studentId = Auth::id();
                $incompleteExercises = $classroom->exercises->filter(function($exercise) use ($studentId, $classroom) {
                    return !\App\Models\StudentSubmission::where('user_id', $studentId)
                        ->where('exercise_id', $exercise->id)
                        ->where('classroom_id', $classroom->id)
                        ->where('is_completed', true)
                        ->exists();
                });
            }

            return view('classroom.show', compact('classroom', 'attendances', 'latestExercise', 'todayExercise', 'incompleteExercises'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Classroom not found: ' . $name);
            return redirect()->route('home')->with('error', 'Kelas tidak ditemukan.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to classroom: ' . $name);
            return redirect()->route('home')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error showing classroom: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat kelas.');
        }
    }

    public function presence($name)
    {
        $classroom = Classroom::where('name', $name)->firstOrFail();
        $students = $classroom->students;
        $today = Carbon::today();
        
        // Get today's attendance records
        $attendances = Attendance::where('classroom_id', $classroom->id)
            ->where('date', $today)
            ->get()
            ->keyBy('user_id');

        return view('classroom.presence', compact('classroom', 'students', 'attendances'));
    }

    public function materials($className)
    {
        $classroom = Classroom::with('materials')->where('name', $className)->firstOrFail();
        $materials = $classroom->materials()->latest()->get();
        return view('classroom.materials', compact('classroom', 'materials'));
    }

    public function verifyPassword(Request $request, $className)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();

            // Allow teachers and admins to bypass password verification
            if (Auth::check()) {
                if (Auth::user()->role === 'admin') {
                    // Admin can always access any classroom
                    return response()->json(['success' => true, 'message' => 'Admin berhasil masuk ke kelas!']);
                }

                if (Auth::user()->role === 'teacher') {
                    // Guru dapat masuk ke kelas manapun
                    return response()->json(['success' => true, 'message' => 'Guru berhasil masuk ke kelas!']);
                }
            }

            // Original student logic for password verification
            if (!Auth::check() || Auth::user()->role !== 'student') {
                return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat bergabung dengan kelas.'], 403);
            }

            if ($classroom->password === $request->input('password')) {
                // Jika password cocok, lampirkan siswa ke kelas
                Auth::user()->classrooms()->syncWithoutDetaching([$classroom->id]);
                return response()->json(['success' => true, 'message' => 'Berhasil masuk ke kelas!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Password salah.'], 401);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memverifikasi password.', 'error' => $e->getMessage()], 500);
        }
    }

    public function getClassrooms()
    {
        $classrooms = Classroom::all(['id', 'name']);
        return response()->json($classrooms);
    }

    public function getClassroomExercises($classroomId)
    {
        try {
            $classroom = Classroom::findOrFail($classroomId);
            $exercises = $classroom->exercises()->with('questions')->get();
            
            return response()->json($exercises);
        } catch (\Exception $e) {
            \Log::error('Error getting classroom exercises: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to load exercises',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exercises($className)
    {
        try {
            $classroom = Classroom::with('exercises')->where('name', $className)->firstOrFail();
            return view('classroom.exercises', compact('classroom'));
        } catch (\Exception $e) {
            Log::error('Error loading classroom exercises: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load exercises');
        }
    }

    public function removeExercise($classroomId, $exerciseId)
    {
        try {
            \Log::info('Attempting to remove exercise', [
                'classroom_id' => $classroomId,
                'exercise_id' => $exerciseId
            ]);

            $classroom = Classroom::findOrFail($classroomId);
            
            // Check if the exercise exists in the classroom
            if (!$classroom->exercises()->where('exercise_id', $exerciseId)->exists()) {
                \Log::warning('Exercise not found in classroom', [
                    'classroom_id' => $classroomId,
                    'exercise_id' => $exerciseId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Exercise not found in this classroom'
                ], 404);
            }

            // Remove the exercise from the classroom
            $classroom->exercises()->detach($exerciseId);

            \Log::info('Exercise removed successfully', [
                'classroom_id' => $classroomId,
                'exercise_id' => $exerciseId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Exercise successfully removed from classroom'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error removing exercise from classroom: ' . $e->getMessage(), [
                'classroom_id' => $classroomId,
                'exercise_id' => $exerciseId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove exercise from classroom'
            ], 500);
        }
    }

    public function grades($className)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            
            // Get all submissions for exercises in this classroom for the current student
            $submissions = StudentSubmission::with('exercise')
                ->where('user_id', Auth::id())
                ->where('classroom_id', $classroom->id)
                ->where('is_completed', true)
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('classroom.grades', compact('classroom', 'submissions'));
        } catch (\Exception $e) {
            Log::error('Error loading grades: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load grades');
        }
    }

    public function addStudent(Request $request, $className)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'name')->where(function ($query) {
                        return $query->where('role', 'student');
                    })
                ]
            ], [
                'name.required' => 'Nama siswa wajib diisi.',
                'name.unique' => 'Nama siswa sudah digunakan.',
                'name.max' => 'Nama siswa maksimal 255 karakter.'
            ]);

            $classroom = Classroom::where('name', $className)->firstOrFail();
            
            // Verify teacher access
            if (Auth::id() !== $classroom->teacher_id) {
                throw new \Illuminate\Auth\Access\AuthorizationException('Anda tidak memiliki akses untuk menambah siswa.');
            }

            // Create new student user
            $student = User::create([
                'name' => $validated['name'],
                'email' => strtolower(str_replace(' ', '', $validated['name'])) . '@student.englicious.com',
                'password' => bcrypt('student123'), // Default password
                'role' => 'student'
            ]);

            // Attach student to classroom
            $classroom->students()->attach($student->id);

            // Create initial attendance record for today
            $today = Carbon::today();
            Attendance::create([
                'user_id' => $student->id,
                'classroom_id' => $classroom->id,
                'status' => 'present',
                'date' => $today
            ]);

            // Log the student addition
            Log::info('Student added to classroom', [
                'student_id' => $student->id,
                'classroom_id' => $classroom->id,
                'added_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'student' => $student
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->errors()['name'][0] ?? 'Validasi gagal'
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding student: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambah siswa'
            ], 500);
        }
    }

    public function updateStudent(Request $request, $className, $studentId)
    {
        try {
            $student = User::where('role', 'student')->findOrFail($studentId);
            
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('role', 'student');
                    })->ignore($student->id)
                ]
            ], [
                'name.unique' => 'A student with this name already exists.'
            ]);

            $student->update([
                'name' => $validated['name'],
                'email' => strtolower(str_replace(' ', '', $validated['name'])) . '@student.englicious.com'
            ]);

            return response()->json([
                'success' => true,
                'student' => $student,
                'message' => 'Student updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['name'][0] ?? 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student'
            ], 500);
        }
    }

    public function removeStudent($className, $studentId)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $student = User::where('role', 'student')->findOrFail($studentId);

            // Remove student from classroom
            $classroom->students()->detach($student->id);

            // Soft delete the student
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => "Student has been removed successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing student: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove student'
            ], 500);
        }
    }

    public function takeExercise($classroom, $exercise)
    {
        $classroom = Classroom::where('name', $classroom)->firstOrFail();
        $exercise = Exercise::with(['topic', 'subtopic', 'questions'])->findOrFail($exercise);

        // Check if exercise belongs to classroom
        if (!$classroom->exercises()->where('exercise_id', $exercise->id)->exists()) {
            return redirect()->route('classroom.exercises', $classroom->name)
                ->with('error', 'Exercise tidak ditemukan dalam kelas ini.');
        }

        return view('exercises.take', [
            'exercise' => $exercise,
            'classroom' => $classroom
        ]);
    }

    public function submitExercise(Request $request, $classroom, $exercise)
    {
        $classroom = Classroom::where('name', $classroom)->firstOrFail();
        $exercise = Exercise::findOrFail($exercise);

        // Check if exercise belongs to classroom
        if (!$classroom->exercises()->where('exercise_id', $exercise->id)->exists()) {
            return redirect()->route('classroom.exercises', $classroom->name)
                ->with('error', 'Exercise tidak ditemukan dalam kelas ini.');
        }

        try {
            DB::beginTransaction();

            // Validate answers
            $request->validate([
                'answers' => 'required|array',
                'answers.*' => 'required|string'
            ], [
                'answers.required' => 'Jawaban harus diisi',
                'answers.*.required' => 'Semua pertanyaan harus dijawab'
            ]);

            // Get questions
            $questions = $exercise->questions;
            $totalQuestions = $questions->count();
            $correctAnswers = 0;

            // Check answers
            foreach ($questions as $question) {
                $userAnswer = $request->answers[$question->id] ?? null;
                if (isset($userAnswer) && (string)$userAnswer === (string)$question->correct_answer) {
                    $correctAnswers++;
                }
            }

            // Calculate score
            $score = ($correctAnswers / $totalQuestions) * 100;

            // Save submission
            $submission = StudentSubmission::create([
                'user_id' => auth()->id(),
                'exercise_id' => $exercise->id,
                'classroom_id' => $classroom->id,
                'score' => $score,
                'answers' => $request->answers,
                'is_completed' => true,
                'completed_at' => now()
            ]);

            DB::commit();

            return redirect()->route('classroom.exercise.result', [
                'classroom' => $classroom->name,
                'exercise' => $exercise->id
            ])->with('success', 'Jawaban berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim jawaban: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function exerciseResult($className, $exerciseId)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $exercise = Exercise::with('questions')->findOrFail($exerciseId);

            // Get the latest submission
            $submission = StudentSubmission::where('user_id', auth()->id())
                ->where('exercise_id', $exerciseId)
                ->where('classroom_id', $classroom->id)
                ->where('is_completed', true)
                ->latest()
                ->first();

            if (!$submission) {
                return redirect()->route('classroom.exercises', $classroom->name)
                    ->with('error', 'No submission found for this exercise.');
            }

            // Recalculate score including essay scores if available
            $questions = $exercise->questions;
            $totalQuestions = $questions->count();
            $correctOptional = 0;
            $essayQuestionIds = $questions->where('type', 'essay')->pluck('id')->all();
            $allEssayScored = true;
            $essayScores = [];
            foreach ($questions as $question) {
                if ($question->type === 'optional') {
                    if (isset($submission->answers[$question->id]) && (string)$submission->answers[$question->id] === (string)$question->correct_answer) {
                        $correctOptional++;
                    }
                } elseif ($question->type === 'essay') {
                    if (isset($submission->essay_scores[$question->id]) && is_numeric($submission->essay_scores[$question->id])) {
                        $essayScores[] = floatval($submission->essay_scores[$question->id]);
                    } else {
                        $allEssayScored = false;
                    }
                }
            }
            // Kalkulasi score:
            if (count($essayQuestionIds) > 0 && $allEssayScored) {
                // Semua essay sudah dinilai, kalkulasi gabungan
                $essayScoreAvg = count($essayScores) > 0 ? array_sum($essayScores) / count($essayScores) : 0;
                $totalScore = ($correctOptional + ($essayScoreAvg / 100) * count($essayScores)) / $totalQuestions * 100;
                $recalculatedScore = round($totalScore, 2);
            } else {
                // Belum semua essay dinilai, score hanya dari optional
                $recalculatedScore = round(($correctOptional / $totalQuestions) * 100, 2);
            }
            $essayCount = count($essayQuestionIds);

            return view('classroom.exercise-result', [
                'classroom' => $classroom,
                'exercise' => $exercise,
                'submission' => $submission,
                'recalculatedScore' => $recalculatedScore,
                'allEssayScored' => $allEssayScored,
                'essayScores' => $essayScores,
                'correctOptional' => $correctOptional,
                'essayCount' => $essayCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error in exerciseResult: ' . $e->getMessage());
            return redirect()->route('classroom.exercises', $className)
                ->with('error', 'Error loading exercise result: ' . $e->getMessage());
        }
    }

    public function searchClassroom(Request $request)
    {
        $query = $request->input('query');
        if (Auth::check() && Auth::user()->role === 'student') {
            // Student hanya bisa mencari classroom yang belum diikuti
            $joinedIds = Auth::user()->classrooms()->pluck('classrooms.id')->toArray();
            $classrooms = \App\Models\Classroom::where('name', 'like', '%' . $query . '%')
                ->whereNotIn('id', $joinedIds)
                ->get(['name', 'description']);
        } else {
            // Teacher/admin bisa cari semua classroom
            $classrooms = \App\Models\Classroom::where('name', 'like', '%' . $query . '%')
                ->get(['name', 'description']);
        }
        return response()->json($classrooms);
    }

    public function leaveClassroom(Request $request, $className)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();

            // Pastikan hanya siswa yang bisa meninggalkan kelas
            if (!Auth::check() || Auth::user()->role !== 'student') {
                return redirect()->back()->with('error', 'Hanya siswa yang dapat meninggalkan kelas.');
            }

            // Hapus relasi siswa dari kelas
            Auth::user()->classrooms()->detach($classroom->id);

            return redirect()->route('classroom.list')->with('success', 'Berhasil keluar dari kelas.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error leaving classroom: ' . $e->getMessage(), [
                'exception' => $e,
                'classroom_name' => $className,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat meninggalkan kelas.');
        }
    }

    public function createTestMaterial()
    {
        try {
            // Check if topics and subtopics exist first
            $topic = \App\Models\Topic::first();
            $subtopic = \App\Models\Subtopic::first();
            
            if (!$topic || !$subtopic) {
                return response()->json([
                    'error' => 'Topics or subtopics not found. Please create them first.'
                ], 400);
            }
            
            $material = Material::create([
                'title' => 'Test Material',
                'description' => 'This is a test material for debugging',
                'file_path' => '/test/file.pdf',
                'topic_id' => $topic->id,
                'subtopic_id' => $subtopic->id,
                'category' => 'Test'
            ]);
            
            return response()->json([
                'success' => true,
                'material' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create test material: ' . $e->getMessage()
            ], 500);
        }
    }

    public function globalSearch(Request $request)
    {
        try {
            $query = $request->input('query');
            
            if (empty($query)) {
                return response()->json([
                    'classrooms' => [],
                    'exercises' => [],
                    'materials' => []
                ]);
            }

            // Search classrooms
            $classrooms = Classroom::select('name', 'description')
                ->where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();

            // Search exercises
            $exercises = Exercise::select('id', 'title', 'description', 'category', 'is_file_upload')
                ->where('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orWhere('category', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();

            // Search materials
            $materials = Material::select('id', 'title', 'description')
                ->where('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();

            return response()->json([
                'classrooms' => $classrooms,
                'exercises' => $exercises,
                'materials' => $materials,
                'debug' => [
                    'query' => $query,
                    'classrooms_count' => $classrooms->count(),
                    'exercises_count' => $exercises->count(),
                    'materials_count' => $materials->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Global search error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage(),
                'classrooms' => [],
                'exercises' => [],
                'materials' => []
            ], 500);
        }
    }

    public function testSearch()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            $results = [];
            
            // Test each model and create test data if needed
            try {
                $classrooms = Classroom::count();
                $results['classrooms'] = $classrooms;
                
                // Create test classroom if none exists
                if ($classrooms == 0) {
                    $user = User::first();
                    if (!$user) {
                        $user = User::create([
                            'name' => 'Test User',
                            'email' => 'test@example.com',
                            'password' => bcrypt('password'),
                            'role' => 'teacher'
                        ]);
                    }
                    
                    Classroom::create([
                        'name' => 'Ignatius Classroom',
                        'description' => 'This is a test classroom for Ignatius',
                        'password' => 'test123',
                        'code' => 'TEST01',
                        'teacher_id' => $user->id
                    ]);
                    
                    Classroom::create([
                        'name' => 'English Class',
                        'description' => 'General English class with Ignatius materials',
                        'password' => 'test123',
                        'code' => 'TEST02',
                        'teacher_id' => $user->id
                    ]);
                    
                    $results['classrooms'] = 2;
                    $results['test_classrooms_created'] = true;
                }
            } catch (\Exception $e) {
                $results['classrooms_error'] = $e->getMessage();
            }
            
            try {
                $exercises = Exercise::count();
                $results['exercises'] = $exercises;
                
                // Create test exercise if none exists
                if ($exercises == 0) {
                    $topic = \App\Models\Topic::first();
                    $subtopic = \App\Models\Subtopic::first();
                    
                    if (!$topic) {
                        $topic = \App\Models\Topic::create([
                            'name' => 'Test Topic',
                            'description' => 'Test topic for exercises',
                            'category' => 'General'
                        ]);
                    }
                    
                    if (!$subtopic) {
                        $subtopic = \App\Models\Subtopic::create([
                            'name' => 'Test Subtopic',
                            'description' => 'Test subtopic for exercises',
                            'topic_id' => $topic->id
                        ]);
                    }
                    
                    Exercise::create([
                        'title' => 'Ignatius Grammar Exercise',
                        'description' => 'Grammar exercise created by Ignatius',
                        'category' => 'Grammar',
                        'topic_id' => $topic->id,
                        'subtopic_id' => $subtopic->id,
                        'is_file_upload' => false
                    ]);
                    
                    Exercise::create([
                        'title' => 'English Vocabulary Test',
                        'description' => 'Vocabulary test with Ignatius content',
                        'category' => 'Vocabulary',
                        'topic_id' => $topic->id,
                        'subtopic_id' => $subtopic->id,
                        'is_file_upload' => false
                    ]);
                    
                    $results['exercises'] = 2;
                    $results['test_exercises_created'] = true;
                }
            } catch (\Exception $e) {
                $results['exercises_error'] = $e->getMessage();
            }
            
            try {
                $materials = Material::count();
                $results['materials'] = $materials;
                
                // Create test material if none exists
                if ($materials == 0) {
                    $topic = \App\Models\Topic::first();
                    $subtopic = \App\Models\Subtopic::first();
                    
                    if (!$topic) {
                        $topic = \App\Models\Topic::create([
                            'name' => 'Test Topic',
                            'description' => 'Test topic for materials',
                            'category' => 'General'
                        ]);
                    }
                    
                    if (!$subtopic) {
                        $subtopic = \App\Models\Subtopic::create([
                            'name' => 'Test Subtopic',
                            'description' => 'Test subtopic for materials',
                            'topic_id' => $topic->id
                        ]);
                    }
                    
                    Material::create([
                        'title' => 'Ignatius Study Material',
                        'description' => 'Study material prepared by Ignatius',
                        'file_path' => '/test/ignatius.pdf',
                        'topic_id' => $topic->id,
                        'subtopic_id' => $subtopic->id,
                        'category' => 'General'
                    ]);
                    
                    Material::create([
                        'title' => 'English Learning Guide',
                        'description' => 'Learning guide with Ignatius methodology',
                        'file_path' => '/test/guide.pdf',
                        'topic_id' => $topic->id,
                        'subtopic_id' => $subtopic->id,
                        'category' => 'General'
                    ]);
                    
                    $results['materials'] = 2;
                    $results['test_materials_created'] = true;
                }
            } catch (\Exception $e) {
                $results['materials_error'] = $e->getMessage();
            }
            
            return response()->json([
                'success' => true,
                'database_connected' => true,
                'results' => $results,
                'message' => 'Test data created successfully. Try searching for "ignatius" now!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'database_connected' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createTestData()
    {
        try {
            // Create test user if not exists
            $user = User::firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'role' => 'teacher'
                ]
            );

            // Create test classrooms
            $classroom1 = Classroom::firstOrCreate(
                ['name' => 'Ignatius Classroom'],
                [
                    'description' => 'This is a test classroom for Ignatius',
                    'password' => 'test123',
                    'code' => 'TEST01',
                    'teacher_id' => $user->id
                ]
            );

            $classroom2 = Classroom::firstOrCreate(
                ['name' => 'English Class'],
                [
                    'description' => 'General English class with Ignatius materials',
                    'password' => 'test123',
                    'code' => 'TEST02',
                    'teacher_id' => $user->id
                ]
            );

            // Create test topic and subtopic
            $topic = \App\Models\Topic::firstOrCreate(
                ['name' => 'Test Topic'],
                [
                    'description' => 'Test topic for exercises and materials',
                    'category' => 'General'
                ]
            );

            $subtopic = \App\Models\Subtopic::firstOrCreate(
                ['name' => 'Test Subtopic'],
                [
                    'description' => 'Test subtopic for exercises and materials',
                    'topic_id' => $topic->id
                ]
            );

            // Create test exercises
            $exercise1 = Exercise::firstOrCreate(
                ['title' => 'Ignatius Grammar Exercise'],
                [
                    'description' => 'Grammar exercise created by Ignatius',
                    'category' => 'Grammar',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'is_file_upload' => false
                ]
            );

            $exercise2 = Exercise::firstOrCreate(
                ['title' => 'English Vocabulary Test'],
                [
                    'description' => 'Vocabulary test with Ignatius content',
                    'category' => 'Vocabulary',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'is_file_upload' => false
                ]
            );

            // Create test materials
            $material1 = Material::firstOrCreate(
                ['title' => 'Ignatius Study Material'],
                [
                    'description' => 'Study material prepared by Ignatius',
                    'file_path' => '/test/ignatius.pdf',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'category' => 'General'
                ]
            );

            $material2 = Material::firstOrCreate(
                ['title' => 'English Learning Guide'],
                [
                    'description' => 'Learning guide with Ignatius methodology',
                    'file_path' => '/test/guide.pdf',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'category' => 'General'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Test data created successfully!',
                'data' => [
                    'classrooms' => [$classroom1->name, $classroom2->name],
                    'exercises' => [$exercise1->title, $exercise2->title],
                    'materials' => [$material1->title, $material2->title]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showDatabaseData()
    {
        try {
            $classrooms = Classroom::select('name', 'description')->get();
            $exercises = Exercise::select('title', 'description', 'category')->get();
            $materials = Material::select('title', 'description')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'classrooms' => $classrooms,
                    'exercises' => $exercises,
                    'materials' => $materials
                ],
                'counts' => [
                    'classrooms' => $classrooms->count(),
                    'exercises' => $exercises->count(),
                    'materials' => $materials->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createSimpleTestData()
    {
        try {
            // Create test user if not exists
            $user = User::firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'role' => 'teacher'
                ]
            );

            // Create test classroom
            $classroom = Classroom::firstOrCreate(
                ['name' => 'English Class'],
                [
                    'description' => 'This is an English class for learning',
                    'password' => 'test123',
                    'code' => 'ENG01',
                    'teacher_id' => $user->id
                ]
            );

            // Create test topic and subtopic
            $topic = \App\Models\Topic::firstOrCreate(
                ['name' => 'General Topic'],
                [
                    'description' => 'General topic for testing',
                    'category' => 'General'
                ]
            );

            $subtopic = \App\Models\Subtopic::firstOrCreate(
                ['name' => 'General Subtopic'],
                [
                    'description' => 'General subtopic for testing',
                    'topic_id' => $topic->id
                ]
            );

            // Create test exercise
            $exercise = Exercise::firstOrCreate(
                ['title' => 'English Grammar Test'],
                [
                    'description' => 'Test your English grammar skills',
                    'category' => 'Grammar',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'is_file_upload' => false
                ]
            );

            // Create test material
            $material = Material::firstOrCreate(
                ['title' => 'English Learning Material'],
                [
                    'description' => 'Comprehensive English learning material',
                    'file_path' => '/test/english.pdf',
                    'topic_id' => $topic->id,
                    'subtopic_id' => $subtopic->id,
                    'category' => 'General'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Test data created successfully!',
                'data' => [
                    'classroom' => $classroom->name,
                    'exercise' => $exercise->title,
                    'material' => $material->title
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


