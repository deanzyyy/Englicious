<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // List schedules for classroom
    public function index($className)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $schedules = $classroom->schedules()->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();
        return view('classroom.schedules.index', compact('classroom', 'schedules'));
    }

    // Create schedule (API)
    public function store(Request $request, $className)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        if (Auth::user()->role !== 'teacher' || Auth::user()->id !== $classroom->teacher_id) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $schedule = $classroom->schedules()->create($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'schedule' => $schedule]);
        }
        return redirect()->route('classroom.schedules.index', $classroom->name)->with('success', 'Schedule created!');
    }

    // Update schedule (API)
    public function update(Request $request, $className, $scheduleId)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $schedule = $classroom->schedules()->findOrFail($scheduleId);
        if (Auth::user()->role !== 'teacher' || Auth::user()->id !== $classroom->teacher_id) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $schedule->update($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'schedule' => $schedule]);
        }
        return redirect()->route('classroom.schedules.index', $classroom->name)->with('success', 'Schedule updated!');
    }

    // Delete schedule (API)
    public function destroy(Request $request, $className, $scheduleId)
    {
        $classroom = Classroom::where('name', $className)->firstOrFail();
        $schedule = $classroom->schedules()->findOrFail($scheduleId);
        if (Auth::user()->role !== 'teacher' || Auth::user()->id !== $classroom->teacher_id) {
            abort(403, 'Unauthorized');
        }
        $schedule->delete();
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('classroom.schedules.index', $classroom->name)->with('success', 'Schedule deleted!');
    }
} 