<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index($className)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $assignments = $classroom->assignments()->latest()->get();
        return view('classroom.assignments.index', compact('classroom', 'assignments'));
    }

    public function create($className)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        return view('classroom.assignments.create', compact('classroom'));
    }

    public function store(Request $request, $className)
    {
        session()->flash('debug', 'AssignmentController@store called: ' . json_encode($request->all()));
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            session()->flash('debug', 'Classroom found: ' . $classroom->id);
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_day' => 'required|integer|between:1,31',
                'due_month' => 'required|integer|between:1,12',
                'due_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),
                // 'file' => 'nullable|mimes:pdf,doc,docx|max:10240',
            ]);
            session()->flash('debug', 'Validation passed');

            $due_date_string = $request->due_year . '-' . $request->due_month . '-' . $request->due_day . ' 23:59:59';
            $due_date = \Carbon\Carbon::parse($due_date_string);
            session()->flash('debug', 'Due date parsed: ' . $due_date);

            // $filePath = null;
            // if ($request->hasFile('file')) {
            //     $filePath = $request->file('file')->store('assignments', 'public');
            // }
            $assignment = Assignment::create([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $due_date,
                // 'file_path' => $filePath,
                'classroom_id' => $classroom->id,
            ]);
            session()->flash('debug', 'Assignment create attempted: ' . json_encode($assignment));

            if (!$assignment || !$assignment->id) {
                session()->flash('error', 'Assignment not created!');
                return redirect()->back()->withInput();
            }

            session()->flash('success', 'Assignment berhasil dibuat!');
            return redirect()->route('classroom.assignments.index', $classroom->name);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation error: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            session()->flash('error', 'Exception: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function show($className, $assignment)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $assignment = Assignment::where('classroom_id', $classroom->id)->findOrFail($assignment);
        return view('classroom.assignments.show', compact('classroom', 'assignment'));
    }

    public function submitAssignment(Request $request, $className, $assignmentId)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $assignment = Assignment::where('classroom_id', $classroom->id)->findOrFail($assignmentId);

        // Asumsi submission_date tetap menggunakan waktu saat ini
        $request->validate([
            'student_file' => 'required|mimes:pdf,doc,docx|max:10240', // max 10MB
        ]);

        $filePath = $request->file('student_file')->store('assignment_submissions', 'public');

        if (!Auth::check() || Auth::user()->role !== 'student') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengumpulkan tugas.');
        }

        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
                                                ->where('user_id', Auth::id())
                                                ->first();

        if ($existingSubmission) {
            if ($existingSubmission->file_path) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
            $existingSubmission->update([
                'file_path' => $filePath,
                'submission_date' => now(), // tetap pakai waktu saat ini
            ]);
            $message = 'Tugas berhasil diperbarui!';
        } else {
            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'user_id' => Auth::id(),
                'file_path' => $filePath,
                'submission_date' => now(), // tetap pakai waktu saat ini
            ]);
            $message = 'Tugas berhasil dikumpulkan!';
        }

        return redirect()->route('classroom.assignments.show', [$classroom->name, $assignment->id])->with('success', $message);
    }

    public function updateSubmissionGrade(Request $request, $className, $assignmentId, $submissionId)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $assignment = Assignment::where('classroom_id', $classroom->id)->findOrFail($assignmentId);
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)->findOrFail($submissionId);

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
        ]);

        $submission->grade = $request->grade;
        $submission->save();

        return redirect()->route('classroom.assignments.show', [$classroom->name, $assignment->id])
            ->with('success', 'Nilai berhasil disimpan!');
    }
}
