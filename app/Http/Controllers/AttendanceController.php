<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $classrooms = Classroom::with(['students', 'attendances' => function($query) {
                $query->whereDate('date', today());
            }])
            ->where('teacher_id', $user->id)
            ->get();

            return view('attendance.index', compact('classrooms'));
        } catch (\Exception $e) {
            Log::error('Error loading attendance list: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat daftar attendance.');
        }
    }

    public function show(Classroom $classroom)
    {
        // Verify access
        if (Auth::id() !== $classroom->teacher_id) {
            throw new \Illuminate\Auth\Access\AuthorizationException('You do not have access to view this attendance.');
        }

        $today = Carbon::today();
        
        // Get today's attendance records
        $attendances = Attendance::where('classroom_id', $classroom->id)
            ->where('date', $today)
            ->with('user')
            ->get()
            ->keyBy('user_id');

        // Get all students in the classroom
        $students = $classroom->students;

        return view('attendance.show', compact('classroom', 'students', 'attendances'));
    }

    public function updateAttendance(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'student_id' => 'required|exists:users,id',
                'classroom_id' => 'required|exists:classrooms,id',
                'status' => 'required|in:present,absent,late,excused',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Verify user has access to this classroom
            $classroom = Classroom::findOrFail($validated['classroom_id']);
            if (Auth::id() !== $classroom->teacher_id) {
                throw new \Illuminate\Auth\Access\AuthorizationException('You do not have access to update attendance.');
            }

            // Verify student belongs to classroom
            if (!$classroom->students()->where('user_id', $validated['student_id'])->exists()) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Student not found in this class.');
            }

            $today = Carbon::today();

            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $validated['student_id'],
                    'classroom_id' => $validated['classroom_id'],
                    'date' => $today
                ],
                [
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function history($classroomId)
    {
        try {
            $classroom = Classroom::with(['students', 'teacher'])->findOrFail($classroomId);
            
            // Verify access
            if (Auth::id() !== $classroom->teacher_id && 
                !$classroom->students()->where('user_id', Auth::id())->exists()) {
                throw new \Illuminate\Auth\Access\AuthorizationException('Anda tidak memiliki akses ke history attendance.');
            }

            // Get attendance records for the last 30 days
            $startDate = Carbon::now()->subDays(30);
            $attendances = Attendance::where('classroom_id', $classroomId)
                ->where('date', '>=', $startDate)
                ->with('user')
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('date');

            // Calculate statistics
            $statistics = [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0
            ];

            foreach ($attendances as $dateAttendances) {
                foreach ($dateAttendances as $attendance) {
                    $statistics[$attendance->status]++;
                }
            }

            return view('attendance.history', compact('classroom', 'attendances', 'statistics'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'Kelas tidak ditemukan.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error loading attendance history: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat history attendance.');
        }
    }

    public function exportPdf($classroomId)
    {
        try {
            $classroom = Classroom::with(['students', 'teacher'])->findOrFail($classroomId);
            
            // Verify access
            if (Auth::id() !== $classroom->teacher_id) {
                throw new \Illuminate\Auth\Access\AuthorizationException('Anda tidak memiliki akses untuk mengexport attendance.');
            }

            $today = Carbon::today();
            $attendances = Attendance::where('classroom_id', $classroomId)
                ->where('date', $today)
                ->with('user')
                ->get();

            $pdf = PDF::loadView('attendance.pdf', [
                'classroom' => $classroom,
                'attendances' => $attendances,
                'date' => $today->format('l, F j, Y')
            ]);

            return $pdf->download('attendance_' . $classroom->name . '_' . $today->format('Y-m-d') . '.pdf');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error exporting attendance PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengexport attendance.');
        }
    }
} 