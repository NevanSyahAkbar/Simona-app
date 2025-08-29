<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Peralatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 1: INFORMASI UTAMA PEKERJAAN                                     --}}
                    {{-- ======================================================================= --}}
                    <div>
                        <p class="text-sm font-medium text-indigo-600">Pekerjaan</p>
                        <h2 class="mt-1 text-3xl font-bold text-gray-900">
                            {{ $peralatan->pekerjaan }}
                        </h2>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                        <div>
                            <p class="font-medium text-gray-500">Tahun Anggaran</p>
                            <p class="mt-1 text-gray-900">{{ $peralatan->tahun }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-500">Mitra/Vendor</p>
                            <p class="mt-1 text-gray-900">{{ $peralatan->mitra ?? 'Belum ada' }}</p>
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 2: STATUS & FINANSIAL                                            --}}
                    {{-- ======================================================================= --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Status Pekerjaan</h4>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $peralatan->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $peralatan->status }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Nilai Pekerjaan (DPP)</h4>
                            <p class="text-xl font-bold text-gray-900">
                                {{-- Mengubah format agar tidak ada desimal untuk Rupiah --}}
                                Rp {{ number_format($peralatan->dpp, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 3: TIMELINE & DOKUMEN PENGADAAN                                  --}}
                    {{-- ======================================================================= --}}
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">🗓️ Timeline & Dokumen</h4>
                        <dl class="space-y-3">
                            {{-- Helper function untuk merender baris detail secara konsisten --}}
                            @php
                                function renderDetailRow($label, $value, $isDate = false) {
                                    $formattedValue = $value;
                                    if ($isDate && $value) {
                                        // Menggunakan isoFormat untuk nama bulan dalam Bahasa Indonesia
                                        $formattedValue = \Carbon\Carbon::parse($value)->isoFormat('D MMMM YYYY');
                                    }

                                    if ($value) {
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm font-semibold text-gray-900 text-right">' . $formattedValue . '</dd></div>';
                                    } else {
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm text-gray-400 italic">Belum diisi</dd></div>';
                                    }
                                }
                            @endphp

                            {{-- Proses Pengadaan --}}
                            {!! renderDetailRow('Tanggal Nota Dinas Izin', $peralatan->nd_ijin, true) !!}
                            {!! renderDetailRow('Tanggal PR (Purchase Request)', $peralatan->date_pr, true) !!}
                            {!! renderDetailRow('Nomor PR', $peralatan->pr_number) !!}
                            {!! renderDetailRow('Nomor PO (Purchase Order)', $peralatan->po_number) !!}
                            {!! renderDetailRow('Nomor GR (Goods Receipt)', $peralatan->gr_string) !!}

                            {{-- Proses Pembayaran --}}
                            <hr class="!my-4 border-dashed">
                            {!! renderDetailRow('Tanggal ND Pembayaran', $peralatan->nd_pembayaran, true) !!}

                        </dl>
                    </div>

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 4: KETERANGAN TAMBAHAN                                           --}}
                    {{-- ======================================================================= --}}
                    @if($peralatan->keterangan)
                    <hr class="my-6 md:my-8 border-gray-200">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">📝 Keterangan</h4>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $peralatan->keterangan }}</p>
                    </div>
                    @endif

                </div>

                {{-- Tombol Aksi di Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('peralatan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
