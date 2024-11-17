<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::get('/auth/login', [UserController::class, 'loginUser']);
