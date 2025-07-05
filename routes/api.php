<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    Route::get('/topics-by-category', [TopicController::class, 'getTopicsByCategory']);
    Route::get('/get-subtopics/{topic}', [TopicController::class, 'getSubtopicsByTopic']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/subtopics-by-topic', [TopicController::class, 'getSubtopicsByTopic']); 