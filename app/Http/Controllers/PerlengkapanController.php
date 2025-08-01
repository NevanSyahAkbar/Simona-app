<?php

namespace App\Http\Controllers;

use App\Models\Perlengkapan;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerlengkapanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perlengkapan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('pekerjaan', 'like', '%' . $request->search . '%')
                  ->orWhere('mitra', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

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

    public function store(Request $request)
    {
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
        ]);

        $validatedData['user_id'] = Auth::id();
        Perlengkapan::create($validatedData);

        return redirect()->route('perlengkapan.index')->with('success', 'Data Perlengkapan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Perlengkapan $perlengkapan)
    {
        // PERUBAHAN: Mengambil nomor urut dari URL
        $rowNumber = $request->query('row', $perlengkapan->id); // Fallback ke ID jika tidak ada

        return view('pages.perlengkapan.show', compact('perlengkapan', 'rowNumber'));
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
        ]);

        $perlengkapan->update($validatedData);

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
