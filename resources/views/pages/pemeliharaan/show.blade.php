<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Pemeliharaan') }}
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
                            {{ $pemeliharaan->pekerjaan }}
                        </h2>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="font-medium text-gray-500">Mitra/Vendor</p>
                            <p class="mt-1 text-gray-900">{{ $pemeliharaan->mitra ?? 'Belum ada' }}</p>
                        </div>
                        <div>
                             <p class="font-medium text-gray-500">Status Pekerjaan</p>
                             <div class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $pemeliharaan->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $pemeliharaan->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200">

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 2: DOKUMEN & PEMBAYARAN                                           --}}
                    {{-- ======================================================================= --}}
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">📄 Dokumen & Pembayaran</h4>
                        <dl class="space-y-3">
                            {{-- Helper function untuk merender baris detail secara konsisten --}}
                            @php
                                function renderDetailRow($label, $value, $isDate = false, $isCurrency = false) {
                                    $formattedValue = $value;
                                    if ($isDate && $value) {
                                        $formattedValue = \Carbon\Carbon::parse($value)->isoFormat('D MMMM YYYY');
                                    } elseif ($isCurrency && is_numeric($value)) {
                                        $formattedValue = 'Rp ' . number_format($value, 0, ',', '.');
                                    }

                                    if ($value) {
                                        $cssClass = $isCurrency ? 'font-bold' : 'font-semibold';
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm ' . $cssClass . ' text-gray-900 text-right">' . $formattedValue . '</dd></div>';
                                    } else {
                                        echo '<div class="flex justify-between items-center"><dt class="text-sm font-medium text-gray-600">' . $label . '</dt><dd class="text-sm text-gray-400 italic">Belum ada</dd></div>';
                                    }
                                }
                            @endphp

                            {{-- Dokumen Terkait --}}
                            {!! renderDetailRow('Laporan Bulanan', $pemeliharaan->laporan_bulanan) !!}
                            {!! renderDetailRow('BAST (Berita Acara Serah Terima)', $pemeliharaan->bast) !!}
                            {!! renderDetailRow('BAPF (Berita Acara Pemeriksaan Fisik)', $pemeliharaan->bapf) !!}
                            {!! renderDetailRow('BAP (Berita Acara Pembayaran)', $pemeliharaan->bap) !!}
                            {!! renderDetailRow('Dokumen Tagihan', $pemeliharaan->dok_tagihan) !!}

                            {{-- Proses Pembayaran --}}
                            <hr class="!my-4 border-dashed">
                            {!! renderDetailRow('Tanggal ND Pembayaran', $pemeliharaan->nd_pembayaran, true) !!}
                            {!! renderDetailRow('Nilai Pekerjaan (DPP)', $pemeliharaan->dpp, false, true) !!}

                        </dl>
                    </div>

                    {{-- ======================================================================= --}}
                    {{-- BAGIAN 3: KETERANGAN TAMBAHAN                                           --}}
                    {{-- ======================================================================= --}}
                    @if($pemeliharaan->keterangan)
                    <hr class="my-6 md:my-8 border-gray-200">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">📝 Keterangan</h4>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $pemeliharaan->keterangan }}</p>
                    </div>
                    @endif

                </div>

                {{-- Tombol Aksi di Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('pemeliharaan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Kembali
                    </a>
                    <a href="{{ route('pemeliharaan.edit', $pemeliharaan->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit Data
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
