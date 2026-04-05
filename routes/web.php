<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BKController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $recommendationCount = $user ? \App\Models\Recommendation::where('user_id', $user->id)->count() : 0;
    $chatCount = $user ? \App\Models\ChatHistory::where('user_id', $user->id)->count() : 0;
    
    return view('dashboard', [
        'recommendationCount' => $recommendationCount,
        'chatCount' => $chatCount
    ]);
})->middleware(['auth', 'verified', 'roleRedirect'])->name('dashboard');

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

// Admin Routes (role-based access control)
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 2. Manajemen Data Siswa
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/students/{id}', [AdminController::class, 'studentDetail'])->name('student.detail');
    Route::get('/students/{id}/chat', [AdminController::class, 'chatHistory'])->name('student.chat');

    // 3. Manajemen Jurusan (CRUD dari database)
    Route::get('/jurusan', [AdminController::class, 'jurusan'])->name('jurusan');
    Route::get('/jurusan/create', [AdminController::class, 'jurusanCreate'])->name('jurusan.create');
    Route::post('/jurusan', [AdminController::class, 'jurusanStore'])->name('jurusan.store');
    Route::get('/jurusan/{id}/edit', [AdminController::class, 'jurusanEdit'])->name('jurusan.edit');
    Route::put('/jurusan/{id}', [AdminController::class, 'jurusanUpdate'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [AdminController::class, 'jurusanDestroy'])->name('jurusan.destroy');

    // 4. Manajemen Akun Guru BK
    Route::get('/guru-bk', [AdminController::class, 'guruBK'])->name('guru-bk');
    Route::get('/guru-bk/create', [AdminController::class, 'guruBKCreate'])->name('guru-bk.create');
    Route::post('/guru-bk', [AdminController::class, 'guruBKStore'])->name('guru-bk.store');
    Route::get('/guru-bk/{id}/edit', [AdminController::class, 'guruBKEdit'])->name('guru-bk.edit');
    Route::put('/guru-bk/{id}', [AdminController::class, 'guruBKUpdate'])->name('guru-bk.update');
    Route::delete('/guru-bk/{id}', [AdminController::class, 'guruBKDestroy'])->name('guru-bk.destroy');

    // 5. Riwayat Rekomendasi Siswa
    Route::get('/riwayat-rekomendasi', [AdminController::class, 'riwayatRekomendasi'])->name('riwayat-rekomendasi');

    // 6. Riwayat Konsultasi Chatbot
    Route::get('/riwayat-chatbot', [AdminController::class, 'riwayatChatbot'])->name('riwayat-chatbot');

    // 7. Profil Admin
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::put('/profil', [AdminController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [AdminController::class, 'updatePassword'])->name('profil.password');
});

// BK Routes (role-based access control)
Route::middleware(['auth', 'verified', 'isBK'])->prefix('bk')->name('bk.')->group(function () {
    Route::get('/dashboard', [BKController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [BKController::class, 'students'])->name('students');
    Route::get('/students/{id}', [BKController::class, 'studentDetail'])->name('student.detail');
    Route::get('/students/{id}/chat', [BKController::class, 'chatHistory'])->name('student.chat');
    Route::get('/riwayat-rekomendasi', [BKController::class, 'riwayatRekomendasi'])->name('riwayat-rekomendasi');
    Route::get('/riwayat-chatbot', [BKController::class, 'riwayatChatbot'])->name('riwayat-chatbot');
    Route::get('/jurusan', [BKController::class, 'jurusan'])->name('jurusan');
    Route::get('/jurusan/create', [BKController::class, 'jurusanCreate'])->name('jurusan.create');
    Route::post('/jurusan', [BKController::class, 'jurusanStore'])->name('jurusan.store');
    Route::get('/jurusan/{id}/edit', [BKController::class, 'jurusanEdit'])->name('jurusan.edit');
    Route::put('/jurusan/{id}', [BKController::class, 'jurusanUpdate'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [BKController::class, 'jurusanDestroy'])->name('jurusan.destroy');
    Route::get('/profil', [BKController::class, 'profil'])->name('profil');
    Route::put('/profil', [BKController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [BKController::class, 'updatePassword'])->name('profil.password');
});

require __DIR__.'/auth.php';