<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function() {
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('conversations.messages.store');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::get('/chat/create', [ConversationController::class, 'create'])->name('chat.create');
    Route::post('/chat', [ConversationController::class, 'store'])->name('chat.store');

    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');

});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');