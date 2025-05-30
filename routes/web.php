<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/register-user', [RegisterController::class, 'show'])->name('register.user');
    Route::post('/register-user', [RegisterController::class, 'register'])->name('register.user.submit');
});



Route::get('/registrasi', [AuthController::class, 'tampilRegistrasi'])->name('registrasi.tampil');
Route::post('/registrasi/submit', [AuthController::class, 'submitRegistrasi'])->name('registrasi.submit');

Route::post('/login', [AuthController::class, 'store'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('home');
})->middleware('auth');


Route::get('/home', function () {
    return view('home', ['title' => 'Home']);
});

Route::get('/exercise', function(){
    return view('exercise');
});

Route::get('/presence', function(){
    return view('presence');
});

Route::get('/registrasi', function(){
    return view('registrasi');
});

Route::get('/register', function () {
    return view('registrasi');
})->name('register');


// route::get('/gemini', function(){
//     return view('gemini');
// })->name('gemini');


Route::view('/gemini', 'gemini')->name('gemini');