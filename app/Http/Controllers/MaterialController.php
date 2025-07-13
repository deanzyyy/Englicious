<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Material::with([
                'topic' => function($q) {
                    $q->select('id', 'name', 'category');
                },
                'subtopic' => function($q) {
                    $q->select('id', 'name', 'topic_id');
                },
                'creator'
            ]);

            $allCategories = Topic::select('category')
                ->distinct()
                ->pluck('category');

            $materials = collect(); // Initialize as empty collection
            $noClassroomJoined = false; // Flag for student with no classrooms

            if ($user && $user->role === 'student') {
                $studentClassrooms = $user->classrooms; // Assuming a 'classrooms' relationship on the User model
                if ($studentClassrooms->isEmpty()) {
                    $noClassroomJoined = true;
                } else {
                    $classroomIds = $studentClassrooms->pluck('id')->toArray();
                    $materials = Material::whereHas('classrooms', function ($q) use ($classroomIds) {
                                        $q->whereIn('classrooms.id', $classroomIds);
                                    })
                                    ->with(['topic' => function($q) {
                                        $q->select('id', 'name', 'category');
                                    },
                                    'subtopic' => function($q) {
                                        $q->select('id', 'name', 'topic_id');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                }
            } else {
                // For non-student roles (teacher/admin) or if no user is logged in
                if ($user && $user->role === 'teacher') {
                    // Teachers only see materials they created
                    $materials = $query->where('created_by', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                } elseif ($user && $user->role === 'admin') {
                    // Admins see all materials
                    $materials = $query->orderBy('created_at', 'desc')
                                     ->get();
                } else {
                    // If no user or other roles, fetch all materials (default behavior)
                    $materials = $query->orderBy('created_at', 'desc')
                                     ->get();
                }
            }

            $groupedMaterials = collect();
            if (!$noClassroomJoined) { // Only group materials if student has joined classrooms or if it's not a student
                if ($request->has('category') && $request->category !== 'all') {
                    $filteredMaterials = $materials->filter(function ($material) use ($request) {
                        return optional($material->topic)->category === $request->category;
                    });
                    
                    if ($filteredMaterials->isNotEmpty()) {
                        $groupedMaterials[$request->category] = $filteredMaterials->groupBy(function ($material) {
                            return optional($material->topic)->name ?? 'Uncategorized';
                        });
                    }
                } else {
                    // Show all categories if no filter or 'all' is selected
                    $groupedMaterials = $materials->groupBy(function ($material) {
                        return optional($material->topic)->category ?? 'Uncategorized';
                    })->map(function ($categoryMaterials) {
                        return $categoryMaterials->groupBy(function ($material) {
                            return optional($material->topic)->name ?? 'Uncategorized';
                        });
                    });
                }
            }

            return view('materials.index', [
                'materials' => $groupedMaterials,
                'allCategories' => $allCategories,
                'total_materials' => $materials->count(),
                'currentCategory' => $request->category ?? 'all',
                'noClassroomJoined' => $noClassroomJoined // Pass the flag to the view
            ]);

        } catch (\Exception $e) {
            Log::error('Error in material index:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('materials.index', [
                'materials' => collect(),
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
            
            return view('materials.create', compact('topics', 'subtopics', 'categories'));
        } catch (\Exception $e) {
            // If there's an error with the category column, try to migrate
            if (str_contains($e->getMessage(), "Unknown column 'category'")) {
                \Artisan::call('migrate:fresh');
                
                $topics = Topic::all()->groupBy('category');
                $subtopics = Subtopic::with('topic')->get();
                $categories = Topic::select('category')->distinct()->pluck('category');
                
                return view('materials.create', compact('topics', 'subtopics', 'categories'));
            }
            throw $e;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string',
                'topic_id' => 'required|exists:topics,id',
                'subtopic_id' => 'required|exists:subtopics,id',
                'pdf_file' => 'required|mimes:pdf|max:10240', // max 10MB
            ]);

            $filePath = $request->file('pdf_file')->store('materials', 'public');

            $material = Material::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'topic_id' => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
                'file_path' => $filePath,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('materials.index')
                ->with('success', 'Material created successfully');

        } catch (\Exception $e) {
            Log::error('Error creating material:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create material: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $material = Material::with(['topic', 'subtopic'])->findOrFail($id);
            return view('materials.show', compact('material'));
        } catch (\Exception $e) {
            return redirect()->route('materials.index')
                ->with('error', 'Material not found');
        }
    }

    public function edit($id)
    {
        try {
            $material = Material::with(['topic', 'subtopic'])->findOrFail($id);
            $topics = Topic::all()->groupBy('category');
            $subtopics = Subtopic::where('topic_id', $material->topic_id)->get();
            
            return view('materials.edit', compact('material', 'topics', 'subtopics'));
        } catch (\Exception $e) {
            return redirect()->route('materials.index')
                ->with('error', 'Material not found');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $material = Material::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string',
                'topic_id' => 'required|exists:topics,id',
                'subtopic_id' => 'required|exists:subtopics,id',
                'pdf_file' => 'nullable|mimes:pdf|max:10240', // max 10MB
            ]);

            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'topic_id' => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
            ];

            if ($request->hasFile('pdf_file')) {
                // Delete old file
                if ($material->file_path) {
                    Storage::disk('public')->delete($material->file_path);
                }
                
                // Store new file
                $filePath = $request->file('pdf_file')->store('materials', 'public');
                $updateData['file_path'] = $filePath;
            }

            $material->update($updateData);

            return redirect()->route('materials.index')
                ->with('success', 'Material updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update material: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Delete the PDF file
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $material->delete();

            return response()->json([
                'success' => true,
                'message' => 'Material deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete material: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubtopics($topicId)
    {
        $subtopics = Subtopic::where('topic_id', $topicId)->get();
        return response()->json($subtopics);
    }

    public function sendToClass(Request $request)
    {
        try {
            $request->validate([
                'material_id' => 'required|exists:materials,id',
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            $material = Material::findOrFail($request->material_id);
            $classroom = Classroom::findOrFail($request->classroom_id);

            // Prevent duplicate entries
            if ($classroom->materials()->where('material_id', $material->id)->exists()) {
                Log::warning('Material already assigned to this classroom', [
                    'material_id' => $material->id,
                    'classroom_id' => $classroom->id,
                    'user_id' => Auth::id()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Materi sudah ada di kelas ini.'
                ], 409); // Conflict status code
            }

            // Attach the material to the classroom
            $classroom->materials()->attach($material->id);

            Log::info('Material successfully sent to classroom', [
                'material_id' => $material->id,
                'classroom_id' => $classroom->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil dikirim ke kelas!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error sending material to classroom', [
                'errors' => $e->errors(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . (current($e->errors())[0] ?? 'Terjadi kesalahan')
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error sending material to classroom', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim materi ke kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromClass($classroomId, $materialId)
    {
        $classroom = \App\Models\Classroom::findOrFail($classroomId);
        $classroom->materials()->detach($materialId);
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil dihapus dari kelas.'
            ]);
        }
        return redirect()->back()->with('success', 'Material berhasil dihapus dari kelas.');
    }

    public function getContent($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Get the full path to the PDF file
            $pdfPath = storage_path('app/public/' . $material->file_path);
            
            // Use pdftotext to convert PDF to text
            $textContent = '';
            if (file_exists($pdfPath)) {
                // Convert PDF to text using shell command
                $textContent = shell_exec("pdftotext \"$pdfPath\" -");
                
                // Clean and format the text content
                $textContent = nl2br(htmlspecialchars($textContent));
            }

            return response()->json([
                'success' => true,
                'title' => $material->title,
                'description' => $material->description,
                'content' => $textContent,
                'file_path' => $material->file_path,
                'category' => optional($material->topic)->category,
                'created_at_formatted' => $material->created_at->diffForHumans()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load material content: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showJson($id)
    {
        try {
            $material = Material::with(['topic', 'subtopic'])->findOrFail($id);

            // Deteksi tipe (file/youtube) jika perlu
            $type = 'file';
            $youtube_id = null;
            if (preg_match('/(?:youtube\\.com\/watch\\?v=|youtu\\.be\/)([\\w-]+)/', $material->file_path, $matches)) {
                $type = 'youtube';
                $youtube_id = $matches[1];
            }

            return response()->json([
                'id' => $material->id,
                'title' => $material->title,
                'description' => $material->description,
                'file_path' => $material->file_path,
                'type' => $type,
                'youtube_id' => $youtube_id,
                'topic' => $material->topic,
                'subtopic' => $material->subtopic,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Material not found'], 404);
        }
    }
}
