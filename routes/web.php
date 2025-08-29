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

    // ===================== PERLENGKAPAN =====================
    Route::resource('perlengkapan', PerlengkapanController::class);
    Route::prefix('perlengkapan')->name('perlengkapan.')->group(function () {
        Route::post('kirim-api/{id}', [PerlengkapanController::class, 'kirimDataPerlengkapan'])->name('kirimApi');
        Route::post('{perlengkapan}/sinkronkan', [PerlengkapanController::class, 'tandaiSinkron'])->name('sinkronkan');
    });

    // ===================== PERALATAN =====================
    Route::resource('peralatan', PeralatanController::class);
    Route::prefix('peralatan')->name('peralatan.')->group(function () {
        Route::post('kirim-api/{id}', [PeralatanController::class, 'kirimDataPeralatan'])->name('kirimApi');
        Route::post('{peralatan}/sinkronkan', [PeralatanController::class, 'tandaiSinkron'])->name('sinkronkan');
    });
    Route::post('/peralatan/kirim-api-bulk', [PeralatanController::class, 'kirimApiBulk'])->name('peralatan.kirimApi.bulk');

    // ===================== PEMELIHARAAN =====================
    Route::resource('pemeliharaan', PemeliharaanController::class);
    Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
        Route::post('kirim-api/{id}', [PemeliharaanController::class, 'kirimDataPemeliharaan'])->name('kirimApi');
        Route::post('{pemeliharaan}/sinkronkan', [PemeliharaanController::class, 'tandaiSinkron'])->name('sinkronkan');
    });

    // ===================== ADMIN ONLY =====================
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
// ========== KODE UNTUK TES DIAGNOSTIK (OPSIONAL) ==================
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
