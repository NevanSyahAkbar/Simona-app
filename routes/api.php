<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\PeralatanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute di sini bersifat stateless dan secara otomatis memiliki prefix '/api'.
| Rute di sini TIDAK dilindungi oleh CSRF.
|
*/

//======================================================================
// RUTE PUBLIK (Tidak Memerlukan Login/Otentikasi)
//======================================================================

// Rute untuk otentikasi
Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'store']);

// Rute untuk menerima data dari aplikasi lain
// Ini adalah endpoint yang Anda tuju dari aplikasi utama.
// Hanya satu definisi yang digunakan, yaitu yang mengarah ke Controller.
Route::post('/perlengkapan', [PerlengkapanController::class, 'storePerlengkapan']);
Route::post('/peralatan', [PeralatanController::class, 'storePeralatan']);



//======================================================================
// RUTE TERLINDUNGI (Wajib Login Menggunakan Sanctum Token)
//======================================================================
Route::middleware('auth:sanctum')->group(function () {

    // Route untuk mendapatkan data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Contoh rute lain yang memerlukan login
    Route::post('/nyetor', [ApiController::class, 'nyetor']);
    Route::post('/attendances', [AttendanceController::class, 'store']);

    // Rute untuk mengelola data perlengkapan (bagi user yang sudah login)
    // Misalnya untuk melihat daftar, detail, mengupdate, dan menghapus.
    Route::get('/perlengkapan', [PerlengkapanController::class, 'index']);
    Route::get('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'show']);
    Route::put('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'update']);
    Route::patch('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'update']);
    Route::delete('/perlengkapan/{perlengkapan}', [PerlengkapanController::class, 'destroy']);


    Route::get('/peralatan', [PeralatanController::class, 'index']);
    Route::get('/peralatan/{peralatan}', [PeralatanController::class, 'show']);
    Route::put('/peralatan/{peralatan}', [PeralatanController::class, 'update']);
    Route::patch('/peralatan/{peralatan}', [PeralatanController::class, 'update']);
    Route::delete('/peralatan/{peralatan}', [PeralatanController::class, 'destroy']);
    
});
