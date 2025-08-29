<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
class PeralatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function apiStore(Request $request)
    {
    // 1. Aturan validasi
         $rules = [
        'tahun' => 'required|digits:4',
        'pekerjaan' => 'required|string|max:255',
        'nd_ijin' => 'nullable|date',
        'date_pr' => 'nullable|date',
        'pr_number' => 'nullable|string|max:255',
        'po_number' => 'nullable|string|max:255',
        'gr_string' => 'nullable|string|max:255',
        'nd_pembayaran' => 'nullable|date',
        'dpp' => 'required|numeric',
        'mitra' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // 2. Generate kode otomatis
        $last = Peralatan::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (int) substr($last->kode_peralatan, 7) + 1 : 1;
        $kode_peralatan = 'CTPK' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 3. Tambahkan user_id dan kode_peralatan
        $validatedData = $validator->validated();
        $validatedData['user_id'] = Auth::id();
        $validatedData['kode_peralatan'] = $kode_peralatan;

        $peralatan = Peralatan::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data Peralatan berhasil ditambahkan via API.',
            'data' => $peralatan
        ], 201);
    }



    public function index(Request $request)
    {
        $query = Peralatan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('pekerjaan', 'like', '%' . $request->search . '%')
                  ->orWhere('mitra', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $peralatan = $query->latest()->paginate(10);
        $statuses = Option::where('type', 'status_peralatan')->get();

        return view('pages.peralatan.index', compact('peralatan', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil data dropdown yang diperlukan
        $statuses = Option::where('type', 'status_peralatan')->get();
        $peralatan = null;

        return view('pages.peralatan.create', compact('peralatan', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     */
    public function kirimDataPeralatan($id) {

            $peralatan = Peralatan::findOrFail($id);

        $payload = [
            'tahun' => $peralatan->tahun,
            'kode_peralatan'=> $peralatan->kode_peralatan,
            'pekerjaan' => $peralatan->pekerjaan,
            'date_nd_ijin' => $peralatan->date_nd_ijin,
            'date_pr' => $peralatan->date_pr,
            'pr_number' => $peralatan->pr_number,
            'po_number' => $peralatan->po_number,
            'gr_string' => $peralatan->gr_string,
            'nd_pembayaran' => $peralatan->nd_pembayaran,
            'dpp' => $peralatan->dpp,
            'mitra' => $peralatan->mitra,
            'status' => $peralatan->status,
            'keterangan' => $peralatan->keterangan
        ];

         $apiUrl = config('app.api_tujuan_url');
        $apiToken = config('app.api_tujuan_token');

        try {
            $response = Http::withToken($apiToken)
                              ->timeout(30)
                              ->post($apiUrl . '/api/peralatan', $payload);

            if ($response->successful()) {
                Log::info('API Call Success: Data berhasil dikirim.');
                $peralatan->sync = true;
                $peralatan->save();
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

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'tahun' => 'required|digits:4',
        'pekerjaan' => 'required|string|max:255',
        'nd_ijin' => 'nullable|date',
        'date_pr' => 'nullable|date',
        'pr_number' => 'nullable|string|max:255',
        'po_number' => 'nullable|string|max:255',
        'gr_string' => 'nullable|string|max:255',
        'nd_pembayaran' => 'nullable|date',
        'dpp' => 'required|numeric',
        'mitra' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
    ]);

    // Generate kode otomatis
    $last = Peralatan::orderBy('id', 'desc')->first();
    $nextNumber = $last ? (int) substr($last->kode_peralatan, 7) + 1 : 1;
    $kode_peralatan = 'CTPK-B-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    $validatedData['user_id'] = Auth::id();
    $validatedData['kode_peralatan'] = $kode_peralatan;

    Peralatan::create($validatedData);

    return redirect()->route('peralatan.index')->with('success', 'Data Peralatan berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(Peralatan $peralatan)
    {
        return view('pages.peralatan.show', compact('peralatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peralatan $peralatan)
    {
        // Mengambil data dropdown yang diperlukan
        $statuses = Option::where('type', 'status_peralatan')->get();

        return view('pages.peralatan.edit', compact('peralatan', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Peralatan $peralatan)
{
    // Aturan validasi Anda
    $validatedData = $request->validate([
        'tahun' => 'required|digits:4',
        'pekerjaan' => 'required|string|max:255',
        'nd_ijin' => 'nullable|date',
        'date_pr' => 'nullable|date',
        'pr_number' => 'nullable|string|max:255',
        'po_number' => 'nullable|string|max:255',
        'gr_string' => 'nullable|string|max:255',
        'nd_pembayaran' => 'nullable|date',
        'dpp' => 'required|numeric',
        'mitra' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
    ]);

    // 1. Update data
    $peralatan->update($validatedData);

    // 2. Reset status sinkronisasi
    $peralatan->sync = false;
    $peralatan->save();

    // 3. Handle respons untuk AJAX atau redirect biasa
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Data Peralatan berhasil diperbarui.',
            'redirect_url' => route('peralatan.index')
        ]);
    }

    return redirect()->route('peralatan.index')->with('success', 'Data Peralatan berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peralatan $peralatan)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $peralatan->delete();
        return redirect()->route('peralatan.index')->with('success', 'Data Peralatan berhasil dihapus.');
    }


    public function kirimApiBulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:peralatan,id', // Memastikan semua ID valid
        ]);

        try {
            $ids = $request->input('ids');

            // Loop melalui setiap ID dan update status sync
            foreach ($ids as $id) {
                $peralatan = Peralatan::find($id);
                if ($peralatan) {
                    // Logika pengiriman API Anda yang sebenarnya akan ada di sini
                    // Untuk sekarang, kita hanya update status sync
                    $peralatan->sync = 1; // atau true
                    $peralatan->save();
                }
            }

            return response()->json(['message' => count($ids) . ' data berhasil disinkronisasi.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Method untuk satu item (jika masih diperlukan)
    public function kirimApi($id)
    {
        try {
            $peralatan = Peralatan::findOrFail($id);
            $peralatan->sync = 1; // atau true
            $peralatan->save();

            return redirect()->route('peralatan.index')->with('success', 'Data berhasil disinkronisasi.');

        } catch (\Exception $e) {
             return redirect()->route('peralatan.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
