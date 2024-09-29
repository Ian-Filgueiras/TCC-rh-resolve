<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login-gateway');
});



Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');;
Route::post('/chatbot', [ChatbotController::class, 'handle']);
