<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;

// Rute Halaman Publik (Frontend)
Route::get('/', [PublicController::class, 'index'])->name('beranda');
Route::get('/tentang', [PublicController::class, 'tentang'])->name('tentang');
Route::get('/struktur', [PublicController::class, 'struktur'])->name('struktur');
Route::get('/anggota', [PublicController::class, 'anggota'])->name('anggota');
Route::get('/agenda', [PublicController::class, 'agenda'])->name('agenda');
Route::get('/agenda/{id}', [PublicController::class, 'agendaDetail'])->name('agenda.detail');
Route::get('/wisata', [PublicController::class, 'wisata'])->name('wisata');
Route::get('/wisata/{id}', [PublicController::class, 'wisataDetail'])->name('wisata.detail');
Route::get('/kotak-saran', function() { return view('frontend.saran'); })->name('saran');
Route::post('/kotak-saran', [PublicController::class, 'saranStore'])->name('saran.store');

// Rute Login & Logout Sederhana
Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.process');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Rute Panel Admin (Dilindungi Middleware Auth)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/anggota', [AdminController::class, 'anggotaIndex'])->name('anggota.index');
    Route::post('/anggota', [AdminController::class, 'anggotaStore'])->name('anggota.store');
    Route::delete('/anggota/{anggota}', [AdminController::class, 'anggotaDestroy'])->name('anggota.destroy');

    Route::get('/agenda', [AdminController::class, 'agendaIndex'])->name('agenda.index');
    Route::post('/agenda', [AdminController::class, 'agendaStore'])->name('agenda.store');
    Route::delete('/agenda/{agenda}', [AdminController::class, 'agendaDestroy'])->name('agenda.destroy');

    Route::get('/wisata', [AdminController::class, 'wisataIndex'])->name('wisata.index');
    Route::post('/wisata', [AdminController::class, 'wisataStore'])->name('wisata.store');
    Route::delete('/wisata/{wisata}', [AdminController::class, 'wisataDestroy'])->name('wisata.destroy');

    Route::get('/saran', [AdminController::class, 'saranIndex'])->name('saran.index');
    Route::delete('/saran/{saran}', [AdminController::class, 'saranDestroy'])->name('saran.destroy');

    Route::get('/cetak', [AdminController::class, 'cetakIndex'])->name('cetak.index');
    Route::post('/cetak/preview', [AdminController::class, 'cetakPreview'])->name('cetak.preview');
}); 