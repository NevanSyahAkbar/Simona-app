<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route untuk login (tidak perlu otentikasi)
Route::post('/login', [ApiController::class, 'login']);

// Route untuk registrasi (tidak perlu otentikasi)
Route::post('/register', [ApiController::class, 'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/nyetor', [ApiController::class, 'nyetor']);
Route::post('/attendances', [AttendanceController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Pastikan route Anda ada DI DALAM group ini
    Route::post('/perlengkapan', [ApiController::class, 'storePerlengkapan']);
    Route::get('/perlengkapan', [ApiController::class, 'getPerlengkapan']);
});

Route::post('/login', function (Request $request) {
    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json(['token' => $token]);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tabel-a', [TabelAController::class, 'store']);
    Route::get('/tabel-a', [TabelAController::class, 'index']);
    // dan seterusnya
});
