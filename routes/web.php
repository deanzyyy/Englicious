<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home', ['title' => 'Home']);
});

Route::get('/exercise', function(){
    return view('exercise');
});

Route::get('/presence', function(){
    return view('presence');
});