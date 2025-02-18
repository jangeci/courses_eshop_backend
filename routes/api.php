<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {

    Route::post('/login', [UserController::class, 'login']);

    //Authentication middleware
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/courses', [CourseController::class, 'courseList']);
        Route::get('/course-detail', [CourseController::class, 'courseDetail']);
        Route::get('/courses-bought', [CourseController::class, 'coursesBought']);
        Route::get('/courses-recommended', [CourseController::class, 'coursesRecommended']);
        Route::get('/search-courses', [CourseController::class, 'coursesSearch']);
        Route::get('/course-lessons', [LessonController::class, 'courseLessons']);
        Route::get('/lesson-detail', [LessonController::class, 'lessonDetail']);
        Route::post('/checkout', [PaymentController::class, 'checkout']);
//        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::get('/video-stream/{fileName}', [VideoController::class, 'streamVideo']);
    Route::any('/web-go-hooks', [PaymentController::class, 'webGoHooks']);
});

