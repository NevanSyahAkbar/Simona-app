<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PemeliharaanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// RUTE PUBLIK (Tidak Perlu Login)
//======================================================================

Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'store']);


//======================================================================
// RUTE TERLINDUNGI (Wajib Login dengan Sanctum Token)
//======================================================================
Route::middleware('auth:sanctum')->group(function () {

    // Mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- MANAJEMEN PERLENGKAPAN ---
    // Menyimpan data perlengkapan baru melalui API
    Route::post('/perlengkapan', [PerlengkapanController::class, 'apiStore']);

    // Mengambil semua data perlengkapan (list)
    Route::get('/perlengkapan', [PerlengkapanController::class, 'index']);

    // Mengambil detail satu data perlengkapan
    Route::get('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'show']);

    // Mengupdate data perlengkapan
    Route::put('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'update']);

    // Menghapus data perlengkapan
    Route::delete('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'destroy']);


    // --- MANAJEMEN PERALATAN ---
    // --- MANAJEMEN PERALATAN ---
// Rute khusus untuk mengirim data yang SUDAH ADA. Perhatikan ada {id} di URL.
Route::post('/peralatan/{id}/kirim', [PeralatanController::class, 'kirimDataPeralatan'])->name('peralatan.kirim');

// Rute standar untuk CRUD (Create, Read, Update, Delete) via API
// Ini akan otomatis membuat POST /peralatan yang mengarah ke fungsi store() atau apiStore()
Route::apiResource('peralatan', PeralatanController::class);


// --- MANAJEMEN PEMELIHARAAN ---
 Route::post('/pemeliharaan', [PemeliharaanController::class, 'apiStore']);
Route::apiResource('pemeliharaan', PemeliharaanController::class);


});
