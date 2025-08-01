<?php

namespace App\Http\Controllers;

use App\Models\Perlengkapan;
use App\Models\Peralatan;
use App\Models\Pemeliharaan;
use App\Models\Anggaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunIni = date('Y');

        // Ambil data terbaru untuk ringkasan, difilter berdasarkan tahun ini
        $perlengkapan = Perlengkapan::whereYear('created_at', $tahunIni)->latest()->take(5)->get();
        $peralatan = Peralatan::whereYear('created_at', $tahunIni)->latest()->take(5)->get();
        $pemeliharaan = Pemeliharaan::whereYear('created_at', $tahunIni)->latest()->take(5)->get();

        // Ambil jumlah total data untuk tahun ini
        $perlengkapanCount = Perlengkapan::whereYear('created_at', $tahunIni)->count();
        $peralatanCount = Peralatan::whereYear('created_at', $tahunIni)->count();
        $pemeliharaanCount = Pemeliharaan::whereYear('created_at', $tahunIni)->count();

        // Hitung Sisa Anggaran untuk tahun ini
        $totalAnggaranPerlengkapan = Anggaran::where('tahun', $tahunIni)->where('modul', 'perlengkapan')->value('total_anggaran') ?? 0;
        $totalDppPerlengkapan = Perlengkapan::whereYear('created_at', $tahunIni)->sum('dpp');
        $sisaAnggaranPerlengkapan = $totalAnggaranPerlengkapan - $totalDppPerlengkapan;

        $totalAnggaranPeralatan = Anggaran::where('tahun', $tahunIni)->where('modul', 'peralatan')->value('total_anggaran') ?? 0;
        $totalDppPeralatan = Peralatan::whereYear('created_at', $tahunIni)->sum('dpp');
        $sisaAnggaranPeralatan = $totalAnggaranPeralatan - $totalDppPeralatan;

        $totalAnggaranPemeliharaan = Anggaran::where('tahun', $tahunIni)->where('modul', 'pemeliharaan')->value('total_anggaran') ?? 0;
        $totalDppPemeliharaan = Pemeliharaan::whereYear('created_at', $tahunIni)->sum('dpp');
        $sisaAnggaranPemeliharaan = $totalAnggaranPemeliharaan - $totalDppPemeliharaan;


        return view('dashboard', compact(
            'perlengkapan', 'peralatan', 'pemeliharaan',
            'perlengkapanCount', 'peralatanCount', 'pemeliharaanCount',
            'sisaAnggaranPerlengkapan', 'sisaAnggaranPeralatan', 'sisaAnggaranPemeliharaan'
        ));
    }
}
