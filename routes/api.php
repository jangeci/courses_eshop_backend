<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {

    Route::post('/login', [UserController::class, 'login']);

    //Authentication middleware
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/courses', [CourseController::class, 'courseList']);
        Route::get('/course-detail', [CourseController::class, 'courseDetail']);
        Route::get('/course-lessons', [LessonController::class, 'courseLessons']);
        Route::get('/lesson-detail', [LessonController::class, 'lessonDetail']);
        Route::get('/video-stream/{fileName}', [VideoController::class, 'streamVideo']);
        Route::post('/logout', [UserController::class, 'logout']);
    });
});

