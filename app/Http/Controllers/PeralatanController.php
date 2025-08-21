<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PeralatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            'pekerjaan' => $peralatan->pekerjaan,
            'date_nd_ijin' => $peralatan->date_nd_ijin,
            'date_pr' => $peralatan->date_pr,
            'pr_number' => $peralatan->pr_number,
            'po_number' => $peralatan->po_number,
            'gr_string' => $peralatan->gr_number,
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
        // PERBAIKAN: Menghapus validasi untuk sub_bagian dan order_padi
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

        $validatedData['user_id'] = Auth::id();
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
        // PERBAIKAN: Menghapus validasi untuk sub_bagian dan order_padi
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

        $peralatan->update($validatedData);

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
}
