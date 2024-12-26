<?php

use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment/success/{id}', [HomeController::class, 'success']);
Route::get('/payment/cancel/{id}',  [HomeController::class, 'cancel']);
