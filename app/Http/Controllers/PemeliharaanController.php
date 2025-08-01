<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeliharaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pemeliharaan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('pekerjaan', 'like', '%' . $request->search . '%')
                  ->orWhere('mitra', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pemeliharaan = $query->latest()->paginate(10);
        // PERUBAHAN: Mengambil status khusus untuk pemeliharaan
        $statuses = Option::where('type', 'status_pemeliharaan')->get();

        return view('pages.pemeliharaan.index', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // PERUBAHAN: Mengambil status khusus untuk pemeliharaan
        $statuses = Option::where('type', 'status_pemeliharaan')->get();
        $pemeliharaan = null;
        return view('pages.pemeliharaan.create', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
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

        $validatedData['user_id'] = Auth::id();
        Pemeliharaan::create($validatedData);

        return redirect()->route('pemeliharaan.index')->with('success', 'Data Pemeliharaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemeliharaan $pemeliharaan)
    {
        return view('pages.pemeliharaan.show', compact('pemeliharaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemeliharaan $pemeliharaan)
    {
        // PERUBAHAN: Mengambil status khusus untuk pemeliharaan
        $statuses = Option::where('type', 'status_pemeliharaan')->get();
        return view('pages.pemeliharaan.edit', compact('pemeliharaan', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
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

        $pemeliharaan->update($validatedData);

        return redirect()->route('pemeliharaan.index')->with('success', 'Data Pemeliharaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemeliharaan $pemeliharaan)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $pemeliharaan->delete();
        return redirect()->route('pemeliharaan.index')->with('success', 'Data Pemeliharaan berhasil dihapus.');
    }
}
