<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\DashboardController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

     Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads/{id}', [LeadController::class, 'show']);
    Route::put('/leads/{id}', [LeadController::class, 'update']);
    Route::delete('/leads/{id}', [LeadController::class, 'destroy']);

// deals

      Route::get('/deals', [DealController::class, 'index']);
    Route::post('/deals', [DealController::class, 'store']);
    Route::get('/deals/{id}', [DealController::class, 'show']);
    Route::put('/deals/{id}', [DealController::class, 'update']);
    Route::delete('/deals/{id}', [DealController::class, 'destroy']);

    // note
     Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/notes/{id}', [NoteController::class, 'show']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

    // ai

     Route::post('/ai/summarize', [AIController::class, 'summarizeNote']);
    Route::post('/ai/suggest', [AIController::class, 'suggestAction']);

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'stats']);
});
