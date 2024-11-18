<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [UserController::class, 'login']);
