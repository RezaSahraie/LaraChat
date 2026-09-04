<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('conversations.messages.store');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');