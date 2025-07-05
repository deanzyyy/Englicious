<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\StudentSubmission;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index($className)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            
            // Check if user is student or teacher
            $isStudent = Auth::user()->role === 'student';
            
            if ($isStudent) {
                // For students, show their own grades
                return $this->showStudentGrades($classroom);
            } else {
                // For teachers, show all students' grades
                return $this->showAllStudentsGrades($classroom);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load grades: ' . $e->getMessage());
        }
    }

    private function showStudentGrades($classroom)
    {
        $userId = Auth::id();
        
        // Get exercise submissions for this student in this classroom
        $exerciseSubmissions = StudentSubmission::with('exercise')
            ->where('user_id', $userId)
            ->where('classroom_id', $classroom->id)
            ->where('is_completed', true)
            ->get();

        // Get assignment submissions for this student in this classroom
        $assignmentSubmissions = AssignmentSubmission::with('assignment')
            ->where('user_id', $userId)
            ->whereHas('assignment', function($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            })
            ->get();

        // Calculate averages
        $avgExercise = $exerciseSubmissions->count() > 0 ? $exerciseSubmissions->avg('score') : 0;
        $avgAssignment = $assignmentSubmissions->whereNotNull('grade')->count() > 0 
            ? $assignmentSubmissions->whereNotNull('grade')->avg('grade') 
            : null;

        // Calculate final score
        $finalScore = null;
        if ($avgAssignment !== null) {
            $finalScore = ($avgExercise * 0.6) + ($avgAssignment * 0.4);
        }

        return view('grades.student', compact(
            'classroom', 
            'exerciseSubmissions', 
            'assignmentSubmissions', 
            'avgExercise', 
            'avgAssignment', 
            'finalScore'
        ));
    }

    private function showAllStudentsGrades($classroom)
    {
        // Get all students in this classroom
        $students = $classroom->students()->orderBy('name')->get();
        
        $studentGrades = [];
        
        foreach ($students as $student) {
            // Get exercise submissions for this student
            $exerciseSubmissions = StudentSubmission::with('exercise')
                ->where('user_id', $student->id)
                ->where('classroom_id', $classroom->id)
                ->where('is_completed', true)
                ->get();

            // Get assignment submissions for this student
            $assignmentSubmissions = AssignmentSubmission::with('assignment')
                ->where('user_id', $student->id)
                ->whereHas('assignment', function($query) use ($classroom) {
                    $query->where('classroom_id', $classroom->id);
                })
                ->get();

            // Calculate averages
            $avgExercise = $exerciseSubmissions->count() > 0 ? $exerciseSubmissions->avg('score') : 0;
            $avgAssignment = $assignmentSubmissions->whereNotNull('grade')->count() > 0 
                ? $assignmentSubmissions->whereNotNull('grade')->avg('grade') 
                : null;

            // Calculate final score
            $finalScore = null;
            if ($avgAssignment !== null) {
                $finalScore = ($avgExercise * 0.6) + ($avgAssignment * 0.4);
            }

            $studentGrades[] = [
                'student' => $student,
                'exerciseSubmissions' => $exerciseSubmissions,
                'assignmentSubmissions' => $assignmentSubmissions,
                'avgExercise' => $avgExercise,
                'avgAssignment' => $avgAssignment,
                'finalScore' => $finalScore
            ];
        }

        return view('grades.teacher', compact('classroom', 'studentGrades'));
    }

    public function updateAssignmentGrade(Request $request, $className, $submissionId)
    {
        try {
            $request->validate([
                'grade' => 'required|numeric|min:0|max:100'
            ]);

            $submission = AssignmentSubmission::findOrFail($submissionId);
            $submission->update([
                'grade' => $request->grade,
                'feedback' => $request->feedback ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentDetails($className, $studentId)
    {
        try {
            $classroom = Classroom::where('name', $className)->firstOrFail();
            $student = User::findOrFail($studentId);
            
            // Get exercise submissions for this student
            $exerciseSubmissions = StudentSubmission::with('exercise')
                ->where('user_id', $student->id)
                ->where('classroom_id', $classroom->id)
                ->where('is_completed', true)
                ->orderBy('updated_at', 'desc')
                ->get();

            // Get assignment submissions for this student
            $assignmentSubmissions = AssignmentSubmission::with('assignment')
                ->where('user_id', $student->id)
                ->whereHas('assignment', function($query) use ($classroom) {
                    $query->where('classroom_id', $classroom->id);
                })
                ->orderBy('submission_date', 'desc')
                ->get();

            // Calculate averages
            $avgExercise = $exerciseSubmissions->count() > 0 ? $exerciseSubmissions->avg('score') : 0;
            $avgAssignment = $assignmentSubmissions->whereNotNull('grade')->count() > 0 
                ? $assignmentSubmissions->whereNotNull('grade')->avg('grade') 
                : null;

            // Calculate final score
            $finalScore = null;
            if ($avgAssignment !== null) {
                $finalScore = ($avgExercise * 0.6) + ($avgAssignment * 0.4);
            }

            $html = view('grades.student_details', compact(
                'student',
                'exerciseSubmissions',
                'assignmentSubmissions',
                'avgExercise',
                'avgAssignment',
                'finalScore'
            ))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load student details: ' . $e->getMessage()
            ], 500);
        }
    }
} 