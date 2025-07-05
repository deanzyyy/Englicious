<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictTeacherData
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $data = $request->all();
                if (isset($data['teacher_id']) && $data['teacher_id'] != Auth::id()) {
                    return response()->json(['error' => 'Akses ditolak.'], 403);
                }
            }
        }
        return $next($request);
    }
} 