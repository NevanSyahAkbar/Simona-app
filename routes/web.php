<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnggaranController; // Tambahkan ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Alihkan halaman utama langsung ke halaman login.
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup untuk semua rute yang memerlukan autentikasi (login)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Rute untuk Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk Modul CRUD (Create, Read, Update, Delete)
    Route::resource('perlengkapan', PerlengkapanController::class);
    Route::resource('peralatan', PeralatanController::class);
    Route::resource('pemeliharaan', PemeliharaanController::class);

    // Rute khusus untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('options', OptionController::class)->except(['show']);
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

        // PERBAIKAN: Tambahkan rute untuk Anggaran di sini
        Route::get('anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
        Route::post('anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');

        Route::put('anggaran/{anggaran}', [AnggaranController::class, 'update'])->name('anggaran.update');
        Route::delete('anggaran/{anggaran}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');
    });
});


