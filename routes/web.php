<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Middleware\EnsureUserIsLoggedIn;



// Rota para exibir o formulário de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Rota para processar o formulário de login
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware([EnsureUserIsLoggedIn::class])->group(function () {

    Route::get('/', function () {
        return view('home');
    });

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');;
    Route::post('/chatbot', [ChatbotController::class, 'handle']);

});
