<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Question;
use App\Models\StudentSubmission;
use App\Models\Classroom;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            // 1. Get base query with eager loading
            $query = Exercise::with([
                'topic' => function($q) {
                    $q->select('id', 'name', 'category');
                },
                'subtopic' => function($q) {
                    $q->select('id', 'name', 'topic_id');
                },
                'questions',
                'creator'
            ]);

            // 2. Get all available categories from topics
            $allCategories = Topic::select('category')
                ->distinct()
                ->pluck('category');

            // 3. Initialize exercises and noClassroomJoined flag
            $exercises = collect();
            $noClassroomJoined = false;

            if ($user && $user->role === 'student') {
                $studentClassrooms = $user->classrooms; // Assuming a 'classrooms' relationship on the User model
                if ($studentClassrooms->isEmpty()) {
                    $noClassroomJoined = true;
                } else {
                    $classroomIds = $studentClassrooms->pluck('id')->toArray();
                    $exercises = Exercise::whereHas('classrooms', function ($q) use ($classroomIds) {
                                        $q->whereIn('classrooms.id', $classroomIds);
                                    })
                                    ->with(['classrooms' => function($q) use ($classroomIds) {
                                        $q->whereIn('classrooms.id', $classroomIds);
                                    }])
                                    ->withCount('questions')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                }
            } else {
                // For non-student roles (teacher/admin) or if no user is logged in
                if ($user && $user->role === 'teacher') {
                    // Teachers only see exercises they created
                    $exercises = $query->where('created_by', $user->id)
                                     ->withCount('questions')
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                } elseif ($user && $user->role === 'admin') {
                    // Admins see all exercises
                    $exercises = $query->withCount('questions')
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                } else {
                    // If no user or other roles, fetch all exercises (default behavior)
                    $exercises = $query->withCount('questions')
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                }
            }

            // 4. Group exercises by category and topic (only if not a student with no classrooms)
            $groupedExercises = collect();
            if (!$noClassroomJoined) {
                if ($request->has('category') && $request->category !== 'all') {
                    $filteredExercises = $exercises->filter(function ($exercise) use ($request) {
                        return optional($exercise->topic)->category === $request->category;
                    });
                    
                    if ($filteredExercises->isNotEmpty()) {
                        $groupedExercises[$request->category] = $filteredExercises->groupBy(function ($exercise) {
                            return optional($exercise->topic)->name ?? 'Uncategorized';
                        });
                    }
                } else {
                    // Show all categories if no filter or 'all' is selected
                    $groupedExercises = $exercises->groupBy(function ($exercise) {
                        return optional($exercise->topic)->category ?? 'Uncategorized';
                    })->map(function ($categoryExercises) {
                        return $categoryExercises->groupBy(function ($exercise) {
                            return optional($exercise->topic)->name ?? 'Uncategorized';
                        });
                    });
                }
            }

            // Pass data to view
            return view('exercises.index', [
                'exercises' => $groupedExercises,
                'allCategories' => $allCategories,
                'total_exercises' => $exercises->count(),
                'currentCategory' => $request->category ?? 'all',
                'noClassroomJoined' => $noClassroomJoined // Pass the flag to the view
            ]);

        } catch (\Exception $e) {
            Log::error('Error in exercise index:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('exercises.index', [
                'exercises' => collect(),
                'allCategories' => collect(),
                'error' => $e->getMessage(),
                'currentCategory' => 'all'
            ]);
        }
    }

    public function create()
    {
        try {
            // Get topics grouped by category
            $topics = Topic::all()->groupBy('category');
            
            // Get all subtopics with their relationships
            $subtopics = Subtopic::with('topic')->get();
            
            // Get all available categories
            $categories = Topic::select('category')->distinct()->pluck('category');
            
            return view('exercises.create', compact('topics', 'subtopics', 'categories'));
        } catch (\Exception $e) {
            // If there's an error with the category column, try to migrate
            if (str_contains($e->getMessage(), "Unknown column 'category'")) {
                \Artisan::call('migrate:fresh');
                
                $topics = Topic::all()->groupBy('category');
                $subtopics = Subtopic::with('topic')->get();
                $categories = Topic::select('category')->distinct()->pluck('category');
                
                return view('exercises.create', compact('topics', 'subtopics', 'categories'));
            }
            throw $e;
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log the incoming request data
            Log::info('Creating exercise with data:', $request->all());

            // Base validation for all exercises
            $baseValidation = [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'topic_id' => 'required|exists:topics,id',
                'subtopic_id' => 'required|exists:subtopics,id',
                'duration' => 'required|integer|min:1',
            ];

            // Check if this is a file upload exercise
            if ($request->has('exercise_file')) {
                // Add file validation rules
                $validated = $request->validate(array_merge($baseValidation, [
                    'exercise_file' => 'required|file|mimes:pdf,ppt,pptx|max:10240', // max 10MB
                ]));

                // Store the file
                $filePath = $request->file('exercise_file')->store('exercise-files', 'public');

                // Create exercise with file
                $exercise = Exercise::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'topic_id' => $validated['topic_id'],
                    'subtopic_id' => $validated['subtopic_id'],
                    'created_by' => Auth::id(),
                    'is_file_upload' => true,
                    'file_path' => $filePath,
                    'duration' => $validated['duration'],
                ]);

                Log::info('File exercise created:', [
                    'exercise_id' => $exercise->id,
                    'file_path' => $filePath
                ]);

            } else {
                // For manual exercise, add question validation
                $validated = $request->validate(array_merge($baseValidation, [
                    'questions' => 'required|array|min:1',
                    'questions.*.question_text' => 'required|string',
                    'questions.*.options' => 'required|array|size:4',
                    'questions.*.options.*' => 'required|string',
                    'questions.*.correct_answer' => 'required|integer|min:0|max:3',
                    // Essay question validation (optional)
                    'essay_questions' => 'array',
                    'essay_questions.*.question_text' => 'required|string',
                ]));

                // Create exercise without file
                $exercise = Exercise::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'topic_id' => $validated['topic_id'],
                    'subtopic_id' => $validated['subtopic_id'],
                    'created_by' => Auth::id(),
                    'is_file_upload' => false,
                    'duration' => $validated['duration'],
                ]);

                Log::info('Manual exercise created:', [
                    'exercise_id' => $exercise->id
                ]);

                // Create optional questions
                foreach ($validated['questions'] as $index => $questionData) {
                    $question = $exercise->questions()->create([
                        'question_text' => $questionData['question_text'],
                        'options' => $questionData['options'],
                        'correct_answer' => $questionData['correct_answer'],
                        'type' => 'optional',
                    ]);

                    // Handle media uploads if present
                    if (isset($questionData['image']) && $questionData['image']) {
                        $imagePath = $questionData['image']->store('question-images', 'public');
                        $question->update(['image_path' => $imagePath]);
                    }

                    if (isset($questionData['audio']) && $questionData['audio']) {
                        $audioPath = $questionData['audio']->store('question-audio', 'public');
                        $question->update(['audio_path' => $audioPath]);
                    }
                }

                // Create essay questions
                if (!empty($validated['essay_questions'])) {
                    foreach ($validated['essay_questions'] as $index => $essayData) {
                        $question = $exercise->questions()->create([
                            'question_text' => $essayData['question_text'],
                            'options' => [],
                            'correct_answer' => null,
                            'type' => 'essay',
                        ]);
                    }
                }
            }

            DB::commit();
            Log::info('Exercise creation completed successfully');

            return redirect()->route('exercises.index')
                ->with('success', 'Exercise berhasil dibuat');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating exercise:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat exercise: ' . $e->getMessage())
                ->withInput();
        }
    }

    // public function tampilKelas() {
    //     $classrooms = Classroom::all(); // Ambil semua classroom
    //     return view('exercises.create', compact('classrooms'));
    // }
        
    public function destroy($id)
    {
        try {
            $exercise = Exercise::findOrFail($id);
            $exercise->delete();

            return response()->json([
                'success' => true,
                'message' => 'Exercise deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete exercise: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $exercise = Exercise::with(['questions', 'topic', 'subtopic'])->findOrFail($id);
            $topics = Topic::all()->groupBy('category');
            $subtopics = Subtopic::where('topic_id', $exercise->topic_id)->get();
            
            return view('exercises.edit', compact('exercise', 'topics', 'subtopics'));
        } catch (\Exception $e) {
            return redirect()->route('exercises.index')
                ->with('error', 'Exercise not found');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Log the incoming request data
            Log::info('Updating exercise with data:', [
                'exercise_id' => $id,
                'request_data' => $request->all()
            ]);

            $exercise = Exercise::findOrFail($id);
            
            // Update exercise details
            $exercise->update([
                'title' => $request->title,
                'description' => $request->description,
                'topic_id' => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
            ]);

            Log::info('Exercise details updated');

            // Handle deleted questions
            if ($request->has('deleted_questions')) {
                $deletedQuestionIds = $request->deleted_questions;
                Log::info('Processing deleted questions:', ['deleted_ids' => $deletedQuestionIds]);

                foreach ($deletedQuestionIds as $questionId) {
                    $question = Question::find($questionId);
                    if ($question) {
                        Log::info('Deleting question:', ['question_id' => $questionId]);
                        
                        // Delete associated files
                        if ($question->image_path && Storage::disk('public')->exists($question->image_path)) {
                            Storage::disk('public')->delete($question->image_path);
                            Log::info('Deleted image file:', ['path' => $question->image_path]);
                        }
                        if ($question->audio_path && Storage::disk('public')->exists($question->audio_path)) {
                            Storage::disk('public')->delete($question->audio_path);
                            Log::info('Deleted audio file:', ['path' => $question->audio_path]);
                        }
                        
                        $question->delete();
                        Log::info('Question deleted successfully');
                    }
                }
            }

            // Update or create questions
            if ($request->has('questions')) {
                foreach ($request->questions as $index => $questionData) {
                    Log::info('Processing question:', ['index' => $index, 'data' => $questionData]);
                    
                    // Skip if this is a deleted question
                    if ($request->has('deleted_questions') && 
                        isset($questionData['id']) && 
                        in_array($questionData['id'], $request->deleted_questions)) {
                        Log::info('Skipping deleted question:', ['question_id' => $questionData['id']]);
                        continue;
                    }

                    $questionId = $questionData['id'] ?? null;
                    
                    // Prepare question data
                    $questionAttributes = [
                        'question_text' => $questionData['question_text'],
                        'options' => $questionData['options'],
                        'correct_answer' => $questionData['correct_answer'],
                    ];

                    if ($questionId) {
                        // Update existing question
                        $question = Question::find($questionId);
                        if ($question) {
                            Log::info('Updating existing question:', ['question_id' => $questionId]);
                            
                            // Handle image upload
                            if (isset($request->file('questions')[$index]['image'])) {
                                $image = $request->file('questions')[$index]['image'];
                                if ($question->image_path && Storage::disk('public')->exists($question->image_path)) {
                                    Storage::disk('public')->delete($question->image_path);
                                }
                                $imagePath = $image->store('question-images', 'public');
                                $questionAttributes['image_path'] = $imagePath;
                                Log::info('Updated question image:', ['path' => $imagePath]);
                            }

                            // Handle audio upload
                            if (isset($request->file('questions')[$index]['audio'])) {
                                $audio = $request->file('questions')[$index]['audio'];
                                if ($question->audio_path && Storage::disk('public')->exists($question->audio_path)) {
                                    Storage::disk('public')->delete($question->audio_path);
                                }
                                $audioPath = $audio->store('question-audio', 'public');
                                $questionAttributes['audio_path'] = $audioPath;
                                Log::info('Updated question audio:', ['path' => $audioPath]);
                            }

                            $question->update($questionAttributes);
                            Log::info('Question updated successfully');
                        }
                    } else {
                        // Create new question
                        Log::info('Creating new question');
                        $question = new Question($questionAttributes);
                        $question->exercise_id = $exercise->id;

                        // Handle image upload for new question
                        if (isset($request->file('questions')[$index]['image'])) {
                            $image = $request->file('questions')[$index]['image'];
                            $imagePath = $image->store('question-images', 'public');
                            $question->image_path = $imagePath;
                            Log::info('Added image to new question:', ['path' => $imagePath]);
                        }

                        // Handle audio upload for new question
                        if (isset($request->file('questions')[$index]['audio'])) {
                            $audio = $request->file('questions')[$index]['audio'];
                            $audioPath = $audio->store('question-audio', 'public');
                            $question->audio_path = $audioPath;
                            Log::info('Added audio to new question:', ['path' => $audioPath]);
                        }

                        $question->save();
                        Log::info('New question created successfully');
                    }
                }
            }

            DB::commit();
            Log::info('Exercise update completed successfully');

            return redirect()->route('exercises.index')->with('success', 'Exercise updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating exercise:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update exercise: ' . $e->getMessage()]);
        }
    }

    public function details($id)
    {
        $exercise = Exercise::with('questions')->findOrFail($id);
        return response()->json($exercise);
    }

    public function take($className, $exerciseId)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $exercise = Exercise::with(['questions', 'classrooms'])->findOrFail($exerciseId);
            
            // Verify that the exercise belongs to the specified classroom
            if (!$exercise->classrooms->contains($classroom)) {
                return redirect()->route('classroom.exercises', $classroom->name)
                    ->with('error', 'This exercise is not available in the selected classroom');
            }
            
            // Get current user's submission if exists for this classroom and exercise
            $submission = null;
            if (Auth::check()) {
                $submission = StudentSubmission::where('user_id', Auth::id())
                    ->where('exercise_id', $exerciseId)
                    ->where('classroom_id', $classroom->id)
                    ->first();
            }
            
            return view('exercises.take', compact('exercise', 'submission', 'classroom'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Error accessing exercise or classroom: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Exercise or Classroom not found or inaccessible');
        } catch (\Exception $e) {
            \Log::error('Error in take method: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An unexpected error occurred.');
        }
    }

    public function submit(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Log semua data request untuk debugging
            Log::info('Submit request data:', $request->all());

            // Validasi input dasar
            $validated = $request->validate([
                'answers' => 'required|array',
                'classroom_id' => 'nullable|exists:classrooms,id'
            ], [
                'answers.required' => 'Please answer at least one question.',
                'classroom_id.exists' => 'Selected classroom does not exist.'
            ]);

            // Get exercise with questions
            $exercise = Exercise::with('questions')->findOrFail($id);

            // Validasi per tipe soal
            $validationErrors = [];
            foreach ($exercise->questions as $question) {
                $userAnswer = $validated['answers'][$question->id] ?? null;
                if ($question->type === 'optional') {
                    if (!is_numeric($userAnswer) || $userAnswer < 0 || $userAnswer > 3) {
                        $validationErrors[] = 'Jawaban pilihan ganda tidak valid.';
                    }
                } elseif ($question->type === 'essay') {
                    if (is_null($userAnswer) || trim($userAnswer) === '') {
                        $validationErrors[] = 'Jawaban essay tidak boleh kosong.';
                    }
                }
            }
            if (!empty($validationErrors)) {
                Log::warning('Validation failed:', $validationErrors);
                return redirect()->back()->withInput()->with('error', implode(' ', $validationErrors));
            }
            
            // Calculate score
            $totalQuestions = $exercise->questions->count();
            $correctAnswers = 0;
            $answers = [];
            
            foreach ($exercise->questions as $question) {
                $userAnswer = $validated['answers'][$question->id] ?? null;
                if ($question->type === 'optional' && isset($userAnswer) && (string)$userAnswer === (string)$question->correct_answer) {
                    $correctAnswers++;
                }
                $answers[$question->id] = $userAnswer;
            }
            
            $score = ($correctAnswers / $totalQuestions) * 100;

            // Cek submission lama
            $existing = StudentSubmission::where('user_id', Auth::id())
                ->where('exercise_id', $id)
                ->where(function($q) use ($validated) {
                    if (array_key_exists('classroom_id', $validated) && $validated['classroom_id']) {
                        $q->where('classroom_id', $validated['classroom_id']);
                    } else {
                        $q->whereNull('classroom_id');
                    }
                })
                ->first();
            if ($existing) {
                $existing->answers = $answers;
                $existing->correct_answers = $correctAnswers;
                $existing->total_questions = $totalQuestions;
                $existing->score = $score;
                $existing->is_completed = true;
                $existing->essay_scores = null;
                $existing->essay_comments = null;
                $existing->save();
                $submission = $existing;
                Log::info('Submission updated (try again)', ['id' => $submission->id]);
            } else {
                $submission = StudentSubmission::create([
                    'user_id' => Auth::id(),
                    'exercise_id' => $id,
                    'classroom_id' => $validated['classroom_id'] ?? null,
                    'answers' => $answers,
                    'correct_answers' => $correctAnswers,
                    'total_questions' => $totalQuestions,
                    'score' => $score,
                    'is_completed' => true
                ]);
                Log::info('Submission created', ['id' => $submission->id]);
            }
            if (!$submission) {
                Log::error('Failed to create/update submission', [
                    'user_id' => Auth::id(),
                    'exercise_id' => $id,
                    'classroom_id' => $validated['classroom_id'] ?? null
                ]);
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menyimpan submission. Silakan coba lagi.');
            }

            DB::commit();

            // Log successful submission
            Log::info('Exercise submitted successfully', [
                'user_id' => Auth::id(),
                'exercise_id' => $id,
                'classroom_id' => $validated['classroom_id'] ?? null,
                'score' => $score,
                'answers_count' => count($answers)
            ]);

            // Redirect to result page
            $classroomId = $validated['classroom_id'] ?? null;
            if (!empty($classroomId)) {
                // For classroom exercises, redirect to classroom exercise result
                $classroom = Classroom::find($classroomId);
                return redirect()->route('classroom.exercise.result', [
                    'className' => $classroom->name,
                    'exerciseId' => $id
                ])->with('success', "Exercise completed! Your score: {$score}% ({$correctAnswers} correct out of {$totalQuestions} questions)");
            } else {
                // For general exercises, redirect to general exercise result
                return redirect()->route('exercises.result', ['id' => $id])
                    ->with('success', "Exercise completed! Your score: {$score}% ({$correctAnswers} correct out of {$totalQuestions} questions)");
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Exercise tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting exercise: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim jawaban: ' . $e->getMessage());
        }
    }

    public function result($id)
    {
        try {
            $exercise = Exercise::with('questions')->findOrFail($id);
            $classroomId = request()->query('classroom_id');
            
            $submission = StudentSubmission::where('exercise_id', $id)
                ->where('user_id', Auth::id())
                ->when($classroomId, function($query) use ($classroomId) {
                    return $query->where('classroom_id', $classroomId);
                }, function($query) {
                    return $query->whereNull('classroom_id');
                })
                ->latest()
                ->firstOrFail();

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
            return view('exercises.result', compact('exercise', 'submission', 'recalculatedScore', 'allEssayScored', 'essayScores', 'correctOptional', 'essayCount'));
        } catch (\Exception $e) {
            return redirect()->route('exercises.index')
                ->with('error', 'Could not find exercise results');
        }
    }

    public function sendToClass(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Log the incoming request data for debugging
            Log::info('Sending exercise to class. Request data:', $request->all());

            $validated = $request->validate([
                'classroom_id' => 'required|exists:classrooms,id'
            ], [
                'classroom_id.required' => 'Pilih satu kelas',
                'classroom_id.exists' => 'Kelas yang dipilih tidak ditemukan'
            ]);

            $exercise = Exercise::findOrFail($id);
            $classroom = Classroom::findOrFail($validated['classroom_id']);

            // Check if exercise is already in the classroom
            if (!$classroom->exercises()->where('exercise_id', $id)->exists()) {
                $classroom->exercises()->attach($id);
                Log::info("Exercise {$id} attached to classroom {$validated['classroom_id']}");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exercise berhasil ditambahkan ke kelas'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error sending exercise to class:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_merge(...array_values($e->errors())))
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending exercise to class: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan exercise ke kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromClass($classroomId, $exerciseId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        $classroom->exercises()->detach($exerciseId);
        
        return response()->json([
            'message' => 'Exercise berhasil dihapus dari kelas',
            'status' => 'success'
        ]);
    }

    public function getClassroomExercises($classroomId)
    {
        $classroom = Classroom::with('exercises')->findOrFail($classroomId);
        return response()->json($classroom->exercises);
    }

    public function removeFromClassroom($classroomId, $exerciseId)
    {
        try {
            $classroom = Classroom::findOrFail($classroomId);
            $exercise = Exercise::findOrFail($exerciseId);

            // Check if the exercise exists in the classroom
            if (!$classroom->exercises()->where('exercise_id', $exerciseId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exercise not found in this classroom'
                ], 404);
            }

            // Remove the exercise from the classroom
            $classroom->exercises()->detach($exerciseId);

            return response()->json([
                'success' => true,
                'message' => 'Exercise successfully removed from classroom'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error removing exercise from classroom: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove exercise from classroom'
            ], 500);
        }
    }

    public function getSubtopics($topicId)
    {
        try {
            $subtopics = Subtopic::where('topic_id', $topicId)->get();
            return response()->json([
                'success' => true,
                'data' => $subtopics
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching subtopics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subtopics'
            ], 500);
        }
    }

    public function takeGeneral($exerciseId)
    {
        $exercise = Exercise::with(['questions', 'topic', 'subtopic'])->findOrFail($exerciseId);
        return view('exercises.take', compact('exercise'));
    }

    public function myResults()
    {
        $submissions = \App\Models\StudentSubmission::with('exercise')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exercises.my_results', compact('submissions'));
    }

    /**
     * Show review page for essay answers (teacher/admin only)
     */
    public function reviewAnswers(Request $request, $className, $exerciseId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['teacher', 'admin'])) {
            abort(403);
        }
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $exercise = Exercise::with(['questions' => function($q) {
                $q->where('type', 'essay');
            }])->findOrFail($exerciseId);
            $essayQuestions = $exercise->questions;

            // Filtering
            $search = $request->input('search');
            $status = $request->input('status'); // reviewed/unreviewed/all

            // Query submissions grouped by student
            $submissionsQuery = StudentSubmission::where('exercise_id', $exerciseId)
                ->where('is_completed', true)
                ->where(function($q) use ($classroom) {
                    $q->where('classroom_id', $classroom->id)
                      ->orWhere(function($q2) use ($classroom) {
                          $q2->whereNull('classroom_id')
                             ->whereHas('user.classrooms', function($q3) use ($classroom) {
                                 $q3->where('classrooms.id', $classroom->id);
                             });
                      });
                })
                ->with('user');
            if ($search) {
                $submissionsQuery->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            }
            if ($status === 'reviewed') {
                $submissionsQuery->whereNotNull('essay_scores');
            } elseif ($status === 'unreviewed') {
                $submissionsQuery->where(function($q) {
                    $q->whereNull('essay_scores')->orWhere('essay_scores', 'like', '%null%');
                });
            }
            // Pagination
            $submissions = $submissionsQuery->orderBy('updated_at', 'desc')->paginate(10);

            return view('classroom.review-essay', compact('classroom', 'exercise', 'essayQuestions', 'submissions', 'search', 'status'));
        } catch (\Exception $e) {
            Log::error('Error in reviewAnswers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading review page: ' . $e->getMessage());
        }
    }

    /**
     * Simpan penilaian essay
     */
    public function submitReview(Request $request, $className, $exerciseId)
    {
        Log::info('submitReview called', [
            'scores' => $request->input('scores', []),
            'comments' => $request->input('comments', []),
            'className' => $className,
            'exerciseId' => $exerciseId,
            'user' => Auth::user()->id ?? null,
        ]);

        $user = Auth::user();
        if (!in_array($user->role, ['teacher', 'admin'])) {
            abort(403);
        }
        
        try {
            DB::beginTransaction();
            
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $exercise = Exercise::findOrFail($exerciseId);
            $scores = $request->input('scores', []);
            $comments = $request->input('comments', []);
            
            // Loop semua student yang mengerjakan
            $studentIds = [];
            foreach ($scores as $questionId => $studentScores) {
                foreach ($studentScores as $studentId => $score) {
                    if (!in_array($studentId, $studentIds)) {
                        $studentIds[] = $studentId;
                    }
                }
            }
            $updatedCount = 0;
            foreach ($studentIds as $studentId) {
                $submissionId = $request->input('submission_ids')[$studentId] ?? null;
                if ($submissionId) {
                    $submission = StudentSubmission::find($submissionId);
                } else {
                    $submission = StudentSubmission::where('exercise_id', $exerciseId)
                        ->where('user_id', $studentId)
                        ->where('is_completed', true)
                        ->where(function($q) use ($classroom) {
                            $q->where('classroom_id', $classroom->id)
                              ->orWhereNull('classroom_id');
                        })
                        ->latest()
                        ->first();
                }
                Log::info('Essay review debug: found submission', [
                    'student_id' => $studentId,
                    'submission_id' => $submission?->id,
                    'essay_scores_before' => $submission?->essay_scores,
                    'essay_comments_before' => $submission?->essay_comments,
                ]);
                if (!$submission) continue;
                $essay_scores = $submission->essay_scores ?? [];
                $essay_comments = $submission->essay_comments ?? [];
                foreach ($scores as $questionId => $studentScores) {
                    if (isset($studentScores[$studentId])) {
                        $essay_scores[$questionId] = $studentScores[$studentId];
                        $essay_comments[$questionId] = $comments[$questionId][$studentId] ?? null;
                    }
                }
                $submission->essay_scores = $essay_scores;
                $submission->essay_comments = $essay_comments;
                // Recalculate score after review
                $exerciseQuestions = $exercise->questions;
                $totalQuestions = $exerciseQuestions->count();
                $correctOptional = 0;
                $essayScoresArr = [];
                $essayQuestionIds = $exerciseQuestions->where('type', 'essay')->pluck('id')->all();
                $allEssayScored = true;
                foreach ($exerciseQuestions as $q) {
                    if ($q->type === 'optional') {
                        if (isset($submission->answers[$q->id]) && (string)$submission->answers[$q->id] === (string)$q->correct_answer) {
                            $correctOptional++;
                        }
                    } elseif ($q->type === 'essay') {
                        if (isset($essay_scores[$q->id]) && is_numeric($essay_scores[$q->id])) {
                            $essayScoresArr[] = floatval($essay_scores[$q->id]);
                        } else {
                            $allEssayScored = false;
                        }
                    }
                }
                if (count($essayQuestionIds) > 0 && $allEssayScored) {
                    $essayScoreAvg = count($essayScoresArr) > 0 ? array_sum($essayScoresArr) / count($essayScoresArr) : 0;
                    $totalScore = ($correctOptional + ($essayScoreAvg / 100) * count($essayScoresArr)) / $totalQuestions * 100;
                    $submission->score = round($totalScore, 2);
                } else {
                    $submission->score = round(($correctOptional / $totalQuestions) * 100, 2);
                }
                $submission->save();
                Log::info('Essay review debug: after save', [
                    'student_id' => $studentId,
                    'submission_id' => $submission->id,
                    'essay_scores_after' => $submission->essay_scores,
                    'essay_comments_after' => $submission->essay_comments,
                ]);
                $updatedCount++;
            }
            
            DB::commit();
            
            Log::info('Essay review submitted successfully', [
                'classroom' => $classroom->name,
                'exercise_id' => $exerciseId,
                'updated_submissions' => $updatedCount,
                'scores_count' => count($scores),
                'comments_count' => count($comments)
            ]);
            
            return redirect()->back()->with('success', 'Essay answers reviewed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting essay review: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error saving review: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        }
    }

    public function exportReview($className, $exerciseId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['teacher', 'admin'])) {
            abort(403);
        }
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $exercise = Exercise::with(['questions' => function($q) {
            $q->where('type', 'essay');
        }])->findOrFail($exerciseId);
        $essayQuestions = $exercise->questions;
        $submissions = StudentSubmission::where('exercise_id', $exerciseId)
            ->where('classroom_id', $classroom->id)
            ->where('is_completed', true)
            ->with('user')
            ->get();
        $data = [];
        foreach ($submissions as $submission) {
            foreach ($essayQuestions as $question) {
                $data[] = [
                    'student' => $submission->user->name ?? 'Unknown',
                    'question' => $question->question_text,
                    'answer' => $submission->answers[$question->id] ?? '',
                    'score' => $submission->essay_scores[$question->id] ?? '',
                    'comment' => $submission->essay_comments[$question->id] ?? '',
                ];
            }
        }
        $pdf = Pdf::loadView('exports.review-essay-pdf', [
            'classroom' => $classroom,
            'exercise' => $exercise,
            'rows' => $data
        ]);
        $filename = 'review_' . $className . '_' . $exerciseId . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
