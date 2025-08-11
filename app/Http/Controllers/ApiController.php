<?php

namespace App\Http\Controllers;

// Import class yang dibutuhkan
use App\Models\User;
use App\Models\Nama;
use App\Models\Perlengkapan; // <-- DITAMBAHKAN
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- DITAMBAHKAN

class ApiController extends Controller
{
    // Method untuk registrasi user baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'gagal menambahkan user'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'penambahan user berhasil',
            'data' => $user
        ], 201);
    }

    // ===================================================================
    // == BAGIAN PERLENGKAPAN YANG DIPERBAIKI DAN DITAMBAHKAN ADA DI SINI ==
    // ===================================================================

    /**
     * Menyimpan data perlengkapan baru milik pengguna yang sedang login.
     */
    public function storePerlengkapan(Request $request)
    {
        // 1. Validasi data yang masuk dari Postman
        $validatedData = $request->validate([
            'tahun' => 'required|digits:4',
            'sub_bagian' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'date_nd_user' => 'nullable|date',
            'date_survey' => 'nullable|date',
            'date_nd_ijin' => 'nullable|date',
            'date_pr' => 'nullable|date',
            'pr_number' => 'nullable|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'gr_number' => 'nullable|string|max:255',
            'order_padi' => 'required|string|max:255',
            'bast_user' => 'nullable|date',
            'nd_pembayaran' => 'nullable|date',
            'dpp' => 'required|numeric',
            'mitra' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            // Tambahkan validasi untuk field lain jika perlu
        ]);

        // 2. Ambil ID pengguna yang terotentikasi dan gabungkan dengan data
        $dataToSave = array_merge($validatedData, [
            'user_id' => Auth::id()
        ]);

        // 3. Simpan ke database menggunakan Model Perlengkapan
        $perlengkapan = Perlengkapan::create($dataToSave);

        // 4. Kembalikan respons sukses dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Perlengkapan berhasil ditambahkan',
            'data' => $perlengkapan
        ], 201);
    }

    /**
     * Mengambil daftar perlengkapan milik pengguna yang sedang login.
     */
    public function getPerlengkapan()
    {
        // Ambil semua data perlengkapan HANYA milik user yang sedang login
        $perlengkapan = Perlengkapan::where('user_id', Auth::id())->latest()->get();

        return response()->json($perlengkapan);
    }

    // ===================================================================
    // == METHOD LAMA ANDA (nyetor) MASIH ADA JIKA DIPERLUKAN ==
    // ===================================================================

    public function nyetor(Request $request)
    {
        $validated = $request->validate([
            'kelamin' => 'required|string|max:250',
            'umur' => 'required|string|max:250',
        ]);

        $item = Nama::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => $item
        ], 201);
    }
}
