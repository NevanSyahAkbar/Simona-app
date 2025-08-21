<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Perlengkapan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 1: INFORMASI UTAMA PEKERJAAN                                     --}}
                    {{-- Informasi paling penting ditampilkan di atas dengan ukuran paling besar. --}}
                    {{-- ======================================================================= --}}
                    <div>
                        <p class="text-sm font-medium text-indigo-600">Pekerjaan</p>
                        <h2 class="mt-1 text-3xl font-bold text-gray-900">
                            {{ $perlengkapan->pekerjaan }}
                        </h2>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                        <div>
                            <p class="font-medium text-gray-500">Sub-Bagian</p>
                            <p class="mt-1 text-gray-900">{{ $perlengkapan->sub_bagian }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-500">Tahun Anggaran</p>
                            <p class="mt-1 text-gray-900">{{ $perlengkapan->tahun }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-500">Mitra/Vendor</p>
                            <p class="mt-1 text-gray-900">{{ $perlengkapan->mitra ?? 'Belum ada' }}</p>
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 2: STATUS & FINANSIAL                                            --}}
                    {{-- Mengelompokkan status akhir dan nilai pekerjaan.                      --}}
                    {{-- ======================================================================= --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Status Pekerjaan</h4>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $perlengkapan->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $perlengkapan->status }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Nilai Pekerjaan (DPP)</h4>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($perlengkapan->dpp, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 3: TIMELINE & DOKUMEN PENGADAAN                                  --}}
                    {{-- Menggunakan format list yang rapi dan label yang jelas.               --}}
                    {{-- ======================================================================= --}}
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">🗓️ Timeline & Dokumen</h4>
                        <dl class="space-y-3">
                            {{-- Menggunakan @if untuk handle data kosong agar lebih rapi --}}
                            @php
                                function renderDetailRow($label, $value, $isDate = false, $isNumber = false) {
                                    $formattedValue = $value;
                                    if ($isDate && $value) {
                                        $formattedValue = \Carbon\Carbon::parse($value)->isoFormat('D MMMM YYYY');
                                    } elseif ($isNumber && $value) {
                                        $formattedValue = $value;
                                    }

                                    if ($value) {
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm font-semibold text-gray-900">' . $formattedValue . '</dd></div>';
                                    } else {
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm text-gray-400 italic">Belum diisi</dd></div>';
                                    }
                                }
                            @endphp

                            {{-- Proses Awal --}}
                            {!! renderDetailRow('Tanggal ND User', $perlengkapan->date_nd_user, true) !!}
                            {!! renderDetailRow('Tanggal Survey', $perlengkapan->date_survey, true) !!}
                            {!! renderDetailRow('Tanggal ND Izin', $perlengkapan->date_nd_ijin, true) !!}

                            {{-- Proses Pengadaan --}}
                            <hr class="!my-4 border-dashed">
                            {!! renderDetailRow('Tanggal PR (Purchase Request)', $perlengkapan->date_pr, true) !!}
                            {!! renderDetailRow('Nomor PR', $perlengkapan->pr_number, false, true) !!}
                            {!! renderDetailRow('Nomor PO (Purchase Order)', $perlengkapan->po_number, false, true) !!}
                            {!! renderDetailRow('Nomor GR (Goods Receipt)', $perlengkapan->gr_number, false, true) !!}
                            {!! renderDetailRow('Order PADI', $perlengkapan->order_padi, false, true) !!}

                            {{-- Proses Serah Terima & Pembayaran --}}
                            <hr class="!my-4 border-dashed">
                            {!! renderDetailRow('Tanggal BAST (Serah Terima)', $perlengkapan->bast_user, true) !!}
                            {!! renderDetailRow('Tanggal ND Pembayaran', $perlengkapan->nd_pembayaran, true) !!}

                        </dl>
                    </div>

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 4: KETERANGAN TAMBAHAN                                           --}}
                    {{-- ======================================================================= --}}
                    @if($perlengkapan->keterangan)
                    <hr class="my-6 md:my-8 border-gray-200">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">📝 Keterangan</h4>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $perlengkapan->keterangan }}</p>
                    </div>
                    @endif

                </div>

                {{-- Tombol Aksi di Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('perlengkapan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Kembali
                    </a>
                    <a href="{{ route('perlengkapan.edit', $perlengkapan->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit Data
                    </a>
            
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
