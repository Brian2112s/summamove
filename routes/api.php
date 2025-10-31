<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\AchievementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/healthz', function() {
    return response('OK', 200);
});

Route::post('/register', [AuthenticationController::class, 'register']);

Route::post('/login', [AuthenticationController::class, 'login']);

Route::post('/logout', [AuthenticationController::class, 'logout']);


Route::get('/exercises', [ExerciseController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [AuthenticationController::class, 'showUsers']);
    Route::delete('/users/{id}', [AuthenticationController::class, 'deleteUser']);
    Route::patch('/users/{id}', [AuthenticationController::class, 'updateUser']);

    Route::post('/exercises', [ExerciseController::class, 'createExercise']);
    Route::delete('/exercises/{id}', [ExerciseController::class, 'deleteExercise']);
    Route::patch('/exercises/{id}', [ExerciseController::class, 'updateExercise']);

    Route::get('/achievements', [AchievementController::class, 'index']);
    Route::get('/achievements/{id}', [AchievementController::class, 'showAchievementsPerUser']);
    Route::post('/achievements/{userId}/{exerciseId}', [AchievementController::class, 'createAchievement']);
    Route::patch('/achievements/{id}', [AchievementController::class, 'updateAchievement']);
    Route::delete('/achievements/{id}', [AchievementController::class, 'deleteAchievement']);
});

