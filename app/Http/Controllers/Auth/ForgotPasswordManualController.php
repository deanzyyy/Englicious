<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordManualController extends Controller
{
    public function checkUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,teacher,student',
        ]);

        $user = User::where('email', $request->email)->where('role', $request->role)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email dan role tidak cocok atau tidak ditemukan.']);
        }
        // Simpan email & role di session untuk proses reset berikutnya
        session(['reset_email' => $user->email, 'reset_role' => $user->role]);
        return redirect()->route('password.manual.form');
    }

    public function showResetForm(Request $request)
    {
        if (!session('reset_email') || !session('reset_role')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Silakan isi form forgot password terlebih dahulu.']);
        }
        return view('auth.reset-password-manual');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $email = session('reset_email');
        $role = session('reset_role');
        $user = User::where('email', $email)->where('role', $role)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User tidak ditemukan.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        // Hapus session reset
        session()->forget(['reset_email', 'reset_role']);
        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
} 