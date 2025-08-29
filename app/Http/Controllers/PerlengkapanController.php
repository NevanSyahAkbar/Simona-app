<?php

namespace App\Http\Controllers;

use App\Models\Perlengkapan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PerlengkapanController extends Controller
{
    /**
     * Menerima data dari API eksternal dan menyimpannya (Update or Create).
     */
    public function apiStore(Request $request)
    {
        // 1. Aturan validasi
        $rules = [
            'kode_perlengkapan' => 'required|string|max:255',
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

        // 2. Lakukan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal pada server tujuan.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 3. Ambil data yang sudah lolos validasi
        $validatedData = $validator->validated();

        // 4. Gunakan updateOrCreate untuk sinkronisasi
        $perlengkapan = Perlengkapan::updateOrCreate(
            ['kode_perlengkapan' => $validatedData['kode_perlengkapan']], // Kunci untuk mencari
            $validatedData // Data untuk diupdate atau dibuat
        );

        // 5. Kembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Data Perlengkapan berhasil disinkronkan via API.',
            'data' => $perlengkapan
        ], 200);
    }

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
     * Menggunakan Route Model Binding untuk efisiensi.
     */
    public function kirimDataPerlengkapan($id) // <-- PERUBAHAN 1
    {
        // Tidak perlu "findOrFail($id)" lagi, karena Laravel sudah melakukannya.
         $perlengkapan = Perlengkapan::findOrFail($id);
        $payload = [
            'kode_perlengkapan' => $perlengkapan->kode_perlengkapan,
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
            'keterangan' => $perlengkapan->keterangan,
        ];

        $apiUrl = config('app.api_tujuan_url');
        $apiToken = config('app.api_tujuan_token');

        try {
            $response = Http::withToken($apiToken)
                              ->timeout(30)
                              ->post($apiUrl . '/api/perlengkapan', $payload);

                              //dd($payload);

            if ($response->successful()) {
                Log::info('API Call Success: Data berhasil dikirim.', ['kode' => $perlengkapan->kode_perlengkapan]);
                $perlengkapan->sync = true;
                $perlengkapan->save();
                return back()->with('success', 'Berhasil mengirim data ke mesin lain!');
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Terjadi kesalahan tidak diketahui.';

                // --- PERUBAHAN 2: Logging payload untuk debug ---
                Log::error("API Call Failed: Status {$response->status()} - {$errorMessage}", [
                    'payload' => $payload
                ]);

                if (isset($errorData['errors'])) {
                    Log::error("API Validation Errors: " . json_encode($errorData['errors']));
                }

                return back()->with('error', "Gagal mengirim data: {$errorMessage}");
            }
        } catch (\Exception $e) {
            Log::error("API Connection Failed: " . $e->getMessage(), [
                'kode' => $perlengkapan->kode_perlengkapan
            ]);
            return back()->with('error', 'Gagal terhubung ke server API tujuan.');
        }
    }

    public function store(Request $request)
    {
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

        $validatedData = $request->validate($rules);

        $last = Perlengkapan::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (int) substr($last->kode_perlengkapan, 7) + 1 : 1;
        $kode_perlengkapan = 'CTPK-A-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $validatedData['user_id'] = Auth::id();
        $validatedData['kode_perlengkapan'] = $kode_perlengkapan;

        Perlengkapan::create($validatedData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perlengkapan berhasil ditambahkan.',
                'redirect_url' => route('perlengkapan.index')
            ]);
        }

        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil ditambahkan.');
    }

    public function show(Perlengkapan $perlengkapan)
    {
        return view('pages.perlengkapan.show', compact('perlengkapan'));
    }

    public function edit(Perlengkapan $perlengkapan)
    {
        $sub_bagians = Option::where('type', 'sub_bagian')->get();
        $order_padis = Option::where('type', 'order_padi')->get();
        $statuses = Option::where('type', 'status')->get();

        return view('pages.perlengkapan.edit', compact('perlengkapan', 'sub_bagians', 'order_padis', 'statuses'));
    }

    public function update(Request $request, Perlengkapan $perlengkapan)
    {
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

        $validatedData = $request->validate($rules);

        $perlengkapan->update($validatedData);

        // Reset status sinkronisasi menjadi 'false' setelah di-update
        $perlengkapan->sync = false;
        $perlengkapan->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data Perlengkapan berhasil diperbarui.',
                'redirect_url' => route('perlengkapan.index')
            ]);
        }

        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil diperbarui.');
    }

    public function destroy(Perlengkapan $perlengkapan)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $perlengkapan->delete();
        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil dihapus.');
    }
}
