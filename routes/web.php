<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnggaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('perlengkapan', PerlengkapanController::class);

// Route untuk mengirim SATU data
    Route::post('perlengkapan/kirim-api/{id}', [PerlengkapanController::class, 'kirimDataPerlengkapan'])->name('perlengkapan.kirimApi');

    Route::post('/perlengkapan/{perlengkapan}/sinkronkan', [PerlengkapanController::class, 'tandaiSinkron'])->name('perlengkapan.sinkronkan');


    Route::resource('peralatan', PeralatanController::class);
    // masukan sourced untuk pengiriman data peralatan
    // routes/web.php atau routes/api.php
    Route::post('peralatan/kirim-api/{id}', [PeralatanController::class, 'kirimDataPeralatan'])->name('peralatan.kirimApi');
    Route::post('/peralatan/{peralatan}/sinkronkan', [PeralatanController::class, 'tandaiSinkron'])->name('peralatan.sinkronkan');

    Route::resource('pemeliharaan', PemeliharaanController::class);

    // Grup khusus untuk admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('options', OptionController::class)->except(['show']);
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

        Route::get('anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
        Route::post('anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
        Route::put('anggaran/{anggaran}', [AnggaranController::class, 'update'])->name('anggaran.update');
        Route::delete('anggaran/{anggaran}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');

        
    });
});


// ===================================================================
// ===== KODE UNTUK TES DIAGNOSTIK (TAMBAHKAN DI SINI) ===============
// ===================================================================

Route::get('/test-sesi', function () {
    session(['test_kunci' => 'Berhasil 123']);
    return 'Sesi berhasil dibuat. Sekarang buka /cek-sesi';
});

Route::get('/cek-sesi', function () {
    $nilai_sesi = session('test_kunci');
    if ($nilai_sesi === 'Berhasil 123') {
        return '✅ Selamat! Sesi Anda berfungsi dengan baik.';
    } else {
        return '❌ PENTING: Sesi Anda GAGAL berfungsi. Ini adalah akar masalahnya.';
    }
});
