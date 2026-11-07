<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminInfaqController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\InfaqController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Rute Halaman Publik (Frontend)
Route::get('/', [PublicController::class, 'index'])->name('beranda');
Route::get('/tentang', [PublicController::class, 'tentang'])->name('tentang');
Route::get('/struktur', [PublicController::class, 'struktur'])->name('struktur');
Route::get('/anggota', [PublicController::class, 'anggota'])->name('anggota');
Route::get('/agenda', [PublicController::class, 'agenda'])->name('agenda');
Route::get('/agenda/{id}', [PublicController::class, 'agendaDetail'])->name('agenda.detail');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/infaq', [InfaqController::class, 'index'])->name('infaq');
Route::get('/infaq/form', [InfaqController::class, 'create'])->name('infaq.create');
Route::post('/infaq', [InfaqController::class, 'store'])->middleware('throttle:5,1')->name('infaq.store');
Route::get('/infaq/transaksi/{token}', [InfaqController::class, 'transaction'])->whereUuid('token')->name('infaq.transaction');
Route::post('/infaq/transaksi/{token}/konfirmasi', [InfaqController::class, 'confirm'])
    ->whereUuid('token')->middleware('throttle:10,1')->name('infaq.confirm');
Route::get('/wisata', [PublicController::class, 'wisata'])->name('wisata');
Route::get('/wisata/{id}', [PublicController::class, 'wisataDetail'])->name('wisata.detail');
Route::get('/kotak-saran', function () {
    return view('frontend.saran');
})->name('saran');
Route::post('/kotak-saran', [PublicController::class, 'saranStore'])->name('saran.store');

// Rute Login & Logout Sederhana
Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.process');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Rute Panel Admin (Dilindungi Middleware Auth)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{berita}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

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

    Route::get('/infaq', [AdminInfaqController::class, 'index'])->name('infaq.index');
    Route::get('/infaq/export', [AdminInfaqController::class, 'export'])->name('infaq.export');
    Route::get('/infaq/{infaq}', [AdminInfaqController::class, 'show'])->name('infaq.show');
    Route::get('/infaq/{infaq}/proof', [AdminInfaqController::class, 'proof'])->name('infaq.proof');
    Route::patch('/infaq/{infaq}/verify', [AdminInfaqController::class, 'verify'])->name('infaq.verify');
    Route::patch('/infaq/{infaq}/reject', [AdminInfaqController::class, 'reject'])->name('infaq.reject');

    Route::get('/cetak', [AdminController::class, 'cetakIndex'])->name('cetak.index');
    Route::post('/cetak/preview', [AdminController::class, 'cetakPreview'])->name('cetak.preview');
});
