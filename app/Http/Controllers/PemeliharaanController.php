<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PemeliharaanController extends Controller
{
    /**
     * Menampilkan daftar data pemeliharaan dengan fitur pencarian dan filter.
     */

    public function apiStore(Request $request)
    {
        // 1. Aturan validasi
        $rules = [
            'kode_pemeliharaan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'laporan_bulanan' => 'nullable|string|max:255',
            'bast' => 'nullable|string|max:255',
            'bapf' => 'nullable|string|max:255',
            'bap' => 'nullable|string|max:255',
            'dok_tagihan' => 'nullable|string|max:255',
            'nd_pembayaran' => 'nullable|date',
            'dpp' => 'required|numeric',
            'mitra' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ];

        // 2. Lakukan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal pada server tujuan.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 3. Ambil data yang sudah lolos validasi
        $validatedData = $validator->validated();

        // 4. Gunakan updateOrCreate untuk sinkronisasi
        $pemeliharaan = Pemeliharaan::updateOrCreate(
            ['kode_pemeliharaan' => $validatedData['kode_pemeliharaan']], // Kunci untuk mencari
            $validatedData // Data untuk diupdate atau dibuat
        );

        // 5. Kembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Data Pemeliharaan berhasil disinkronkan via API.',
            'data' => $pemeliharaan
        ], 200);
    }
    public function index(Request $request)
    {
        $query = Pemeliharaan::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where('pekerjaan', 'like', $searchTerm)
                  ->orWhere('mitra', 'like', $searchTerm);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            });

        $pemeliharaan = $query->latest()->paginate(10);
        $statuses = Option::where('type', 'status_pemeliharaan')->get();

        return view('pages.pemeliharaan.index', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Menampilkan form untuk membuat data pemeliharaan baru.
     */
    public function create()
    {
        $statuses = Option::where('type', 'status_pemeliharaan')->get();
        $pemeliharaan = null;
        return view('pages.pemeliharaan.create', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Menyimpan data pemeliharaan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pekerjaan' => 'required|string|max:255',
            'laporan_bulanan' => 'nullable|string|max:255',
            'bast' => 'nullable|string|max:255',
            'bapf' => 'nullable|string|max:255',
            'bap' => 'nullable|string|max:255',
            'dok_tagihan' => 'nullable|string|max:255',
            'nd_pembayaran' => 'nullable|date',
            'dpp' => 'required|numeric',
            'mitra' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

       $last = Pemeliharaan::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (int) substr($last->kode_pemeliharaan, 7) + 1 : 1;
        $kode_pemeliharaan = 'CTPK-C-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $validatedData['user_id'] = Auth::id();
        $validatedData['kode_pemeliharaan'] = $kode_pemeliharaan;

        Pemeliharaan::create($validatedData);

        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu data pemeliharaan.
     */
    public function show(Pemeliharaan $pemeliharaan)
    {
        return view('pages.pemeliharaan.show', compact('pemeliharaan'));
    }

    /**
     * Menampilkan form untuk mengedit data pemeliharaan.
     */
    public function edit(Pemeliharaan $pemeliharaan)
    {
        $statuses = Option::where('type', 'status_pemeliharaan')->get();
        return view('pages.pemeliharaan.edit', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Memperbarui data pemeliharaan di database.
     */
     public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $validatedData = $request->validate([
            'pekerjaan' => 'required|string|max:255',
            'laporan_bulanan' => 'nullable|string|max:255',
            'bast' => 'nullable|string|max:255',
            'bapf' => 'nullable|string|max:255',
            'bap' => 'nullable|string|max:255',
            'dok_tagihan' => 'nullable|string|max:255',
            'nd_pembayaran' => 'nullable|date',
            'dpp' => 'required|numeric',
            'mitra' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Update data seperti biasa
        $pemeliharaan->update($validatedData);

        // Reset status sinkronisasi menjadi 'false' setelah di-update
        $pemeliharaan->sync = false;
        $pemeliharaan->save();

        // Handle respons untuk AJAX atau redirect biasa
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data Pemeliharaan berhasil diperbarui.',
                'redirect_url' => route('pemeliharaan.index')
            ]);
        }

        return redirect()->route('pemeliharaan.index')->with('success', 'Data Pemeliharaan berhasil diperbarui.');
    }

    /**
     * Menghapus data pemeliharaan dari database.
     */
    public function destroy(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();
        return redirect()->route('pemeliharaan.index')->with('success', 'Data Pemeliharaan berhasil dihapus.');
    }

    /**
     * Mengirim data pemeliharaan spesifik ke API eksternal.
     */
    public function kirimDataPemeliharaan($id)
    {
        $pemeliharaan = Pemeliharaan::findOrFail($id);

        $payload = [
            'kode_pemeliharaan' => $pemeliharaan->kode_pemeliharaan,
            'pekerjaan'       => $pemeliharaan->pekerjaan,
            'laporan_bulanan' => $pemeliharaan->laporan_bulanan,
            'bast'            => $pemeliharaan->bast,
            'bapf'            => $pemeliharaan->bapf,
            'bap'             => $pemeliharaan->bap,
            'dok_tagihan'     => $pemeliharaan->dok_tagihan,
            'nd_pembayaran'   => $pemeliharaan->nd_pembayaran,
            'dpp'             => $pemeliharaan->dpp,
            'mitra'           => $pemeliharaan->mitra,
            'status'          => $pemeliharaan->status,
            'keterangan'      => $pemeliharaan->keterangan,
        ];

        $apiUrl = config('app.api_tujuan_url');
        $apiToken = config('app.api_tujuan_token');

        try {
            // PERBAIKAN: Endpoint diubah ke '/api/pemeliharaan'
            $response = Http::withToken($apiToken)
                              ->timeout(30)
                              ->post($apiUrl . '/api/pemeliharaan', $payload);
                              //dd($payload);

            if ($response->successful()) {
                Log::info('API Call Success: Data pemeliharaan berhasil dikirim.');
                // PERBAIKAN: Baris ini dinonaktifkan untuk mencegah error jika kolom 'sync' tidak ada.
                $pemeliharaan->sync = true;
                $pemeliharaan->save();
                return back()->with('success', 'Berhasil mengirim data pemeliharaan!');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Terjadi kesalahan tidak diketahui.';
                Log::error("API Call Failed: Status {$response->status()} - {$errorMessage}");
                return back()->with('error', "Gagal mengirim data: {$errorMessage}");
            }
        } catch (\Exception $e) {
            Log::error("API Connection Failed: " . $e->getMessage());
            return back()->with('error', 'Gagal terhubung ke server API tujuan.');
        }
    }

}
