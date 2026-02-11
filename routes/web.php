<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $recommendationCount = \App\Models\Recommendation::where('user_id', $user->id)->count();
    $chatCount = \App\Models\ChatHistory::where('user_id', $user->id)->count();
    
    return view('dashboard', [
        'recommendationCount' => $recommendationCount,
        'chatCount' => $chatCount
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rekomendasi Routes
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/proses', [RekomendasiController::class, 'proses'])->name('rekomendasi.proses');

    // Chatbot Routes
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

    // History Routes
    Route::get('/history/rekomendasi', [RekomendasiController::class, 'historyRekomendasi'])->name('history.rekomendasi');
    Route::get('/history/chat', [ChatbotController::class, 'historyChat'])->name('history.chat');
});

require __DIR__.'/auth.php';