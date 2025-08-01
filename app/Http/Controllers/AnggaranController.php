<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        $anggarans = Anggaran::latest()->get()->groupBy('tahun');

        // Ambil data anggaran yang akan diedit jika ada parameter 'edit'
        $anggaranToEdit = null;
        if ($request->has('edit')) {
            $anggaranToEdit = Anggaran::find($request->edit);
        }

        return view('pages.anggaran.index', compact('anggarans', 'anggaranToEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4',
            'modul' => 'required|in:perlengkapan,peralatan,pemeliharaan',
            'total_anggaran' => 'required|numeric|min:0',
        ]);

        Anggaran::updateOrCreate(
            [
                'tahun' => $request->tahun,
                'modul' => $request->modul,
            ],
            [
                'total_anggaran' => $request->total_anggaran,
            ]
        );

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anggaran $anggaran)
    {
        $request->validate([
            'total_anggaran' => 'required|numeric|min:0',
        ]);

        $anggaran->update($request->only('total_anggaran'));

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggaran $anggaran)
    {
        $anggaran->delete();

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil dihapus.');
    }
}
