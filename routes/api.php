<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {

    Route::post('/login', [UserController::class, 'login']);

    //Authentication middleware
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/courses', [CourseController::class, 'courseList']);
        Route::get('/course-detail', [CourseController::class, 'courseDetail']);
        Route::post('/logout', [UserController::class, 'logout']);
    });

});

