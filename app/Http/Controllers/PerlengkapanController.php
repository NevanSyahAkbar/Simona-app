<?php

namespace App\Http\Controllers;

use App\Models\Perlengkapan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator; // <-- DITAMBAHKAN
use Illuminate\Validation\ValidationException; // <-- DITAMBAHKAN

class PerlengkapanController extends Controller
{
    /**
     * Menampilkan daftar data perlengkapan.
     */
    public function index(Request $request)
    {
        $query = Perlengkapan::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('pekerjaan', 'like', $searchTerm)
                             ->orWhere('mitra', 'like', $searchTerm);
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            });

        $perlengkapan = $query->latest()->paginate(10);
        $statuses = Option::where('type', 'status')->get();

        return view('pages.perlengkapan.index', compact('perlengkapan', 'statuses'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        $sub_bagians = Option::where('type', 'sub_bagian')->get();
        $order_padis = Option::where('type', 'order_padi')->get();
        $statuses = Option::where('type', 'status')->get();
        $perlengkapan = null;

        return view('pages.perlengkapan.create', compact('perlengkapan', 'sub_bagians', 'order_padis', 'statuses'));
    }

    /**
     * Mengirim data ke API eksternal.
     */
    public function kirimDataPerlengkapan($id) {
        $perlengkapan = Perlengkapan::findOrFail($id);

        $payload = [
            'tahun' => $perlengkapan->tahun,
            'sub_bagian' => $perlengkapan->sub_bagian,
            'pekerjaan' => $perlengkapan->pekerjaan,
            'date_nd_user' => $perlengkapan->date_nd_user,
            'date_survey' => $perlengkapan->date_survey,
            'date_nd_ijin' => $perlengkapan->date_nd_ijin,
            'date_pr' => $perlengkapan->date_pr,
            'pr_number' => $perlengkapan->pr_number,
            'po_number' => $perlengkapan->po_number,
            'gr_number' => $perlengkapan->gr_number,
            'order_padi' => $perlengkapan->order_padi,
            'bast_user' => $perlengkapan->bast_user,
            'nd_pembayaran' => $perlengkapan->nd_pembayaran,
            'dpp' => $perlengkapan->dpp,
            'mitra' => $perlengkapan->mitra,
            'status' => $perlengkapan->status,
            'keterangan' => $perlengkapan->keterangan
        ];

        $apiUrl = config('app.api_tujuan_url');
        $apiToken = config('app.api_tujuan_token');

        try {
            $response = Http::withToken($apiToken)
                              ->timeout(30)
                              ->post($apiUrl . '/api/perlengkapan', $payload);

            if ($response->successful()) {
                Log::info('API Call Success: Data berhasil dikirim.');
                $perlengkapan->sync = true;
                $perlengkapan->save();
                return back()->with('success', 'Berhasil mengirim data ke mesin lain!');
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

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        // ================================= PERUBAHAN DIMULAI =================================
        $rules = [
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
        ];

        // Validasi manual
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal
        if ($validator->fails()) {
            // Dan jika request berasal dari AJAX/JavaScript
            if ($request->expectsJson()) {
                // Kembalikan error dalam format JSON
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }
            // Jika request biasa, biarkan Laravel me-redirect seperti default
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();
        // ====================================================================================

        $validatedData['user_id'] = Auth::id();
        Perlengkapan::create($validatedData);

        // ================================= PERUBAHAN DIMULAI =================================
        // Cek jika request berasal dari AJAX/JavaScript
        if ($request->expectsJson()) {
            // Kembalikan response sukses dalam format JSON
            return response()->json([
                'message' => 'Data Perlengkapan berhasil ditambahkan.',
                'redirect_url' => route('perlengkapan.index')
            ]);
        }
        // ====================================================================================

        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail data.
     */
    public function show(Perlengkapan $perlengkapan)
    {
        return view('pages.perlengkapan.show', compact('perlengkapan'));
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(Perlengkapan $perlengkapan)
    {
        $sub_bagians = Option::where('type', 'sub_bagian')->get();
        $order_padis = Option::where('type', 'order_padi')->get();
        $statuses = Option::where('type', 'status')->get();

        return view('pages.perlengkapan.edit', compact('perlengkapan', 'sub_bagians', 'order_padis', 'statuses'));
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, Perlengkapan $perlengkapan)
    {
        // ================================= PERUBAHAN DIMULAI =================================
        $rules = [
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
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $validatedData = $validator->validated();
        // ====================================================================================

        $perlengkapan->update($validatedData);

        // ================================= PERUBAHAN DIMULAI =================================
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data Perlengkapan berhasil diperbarui.',
                'redirect_url' => route('perlengkapan.index')
            ]);
        }
        // ====================================================================================

        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(Perlengkapan $perlengkapan)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $perlengkapan->delete();
        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil dihapus.');
    }
}
