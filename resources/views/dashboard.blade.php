<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <p class="text-sm text-gray-600">
            Ringkasan data terbaru dari setiap modul.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card Perlengkapan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col transition hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Perlengkapan</h3>
                                <span class="text-sm text-gray-500">({{ $perlengkapanCount }} Data)</span>
                            </div>
                            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list"><line x1="8" x2="8" y1="2" y2="4"/><line x1="16" x2="16" y1="2" y2="4"/><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="12" y1="10" y2="10"/><line x1="8" x2="12" y1="14" y2="14"/><line x1="16" x2="16" y1="10" y2="10"/><line x1="16" x2="16" y1="14" y2="14"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-4 flex-grow">
                        {{-- PERBAIKAN: Menambahkan judul kolom --}}
                        <div class="flex justify-between text-sm font-semibold text-gray-500 mb-2 border-b pb-2">
                            <span>Pekerjaan</span>
                            <span>Status</span>
                        </div>
                        <div class="space-y-3 mt-3">
                            @forelse ($perlengkapan as $item)
                                <div class="flex justify-between items-center">
                                    <p class="text-sm text-gray-800 truncate pr-2">{{ $item->pekerjaan }}</p>
                                    <span class="flex-shrink-0 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Tidak Ada Data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-200 mt-auto">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Sisa Anggaran</p>
                                <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($sisaAnggaranPerlengkapan, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('anggaran.index') }}" class="text-sm text-blue-600 hover:underline">Kelola</a>
                        </div>
                        <a href="{{ route('perlengkapan.index') }}" class="block text-center text-blue-600 hover:underline font-semibold">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card Peralatan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col transition hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Peralatan</h3>
                                <span class="text-sm text-gray-500">({{ $peralatanCount }} Data)</span>
                            </div>
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4h-8v-4h-4V9Z"/><path d="M18 18h1c.6 0 1-.4 1-1v-3.65c0-.22-.08-.44-.23-.62l-2.48-2.73c-.19-.21-.49-.35-.8-.35H14"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-4 flex-grow">
                        {{-- PERBAIKAN: Menambahkan judul kolom --}}
                        <div class="flex justify-between text-sm font-semibold text-gray-500 mb-2 border-b pb-2">
                            <span>Pekerjaan</span>
                            <span>Status</span>
                        </div>
                        <div class="space-y-3 mt-3">
                            @forelse ($peralatan as $item)
                                <div class="flex justify-between items-center">
                                    <p class="text-sm text-gray-800 truncate pr-2">{{ $item->pekerjaan }}</p>
                                    <span class="flex-shrink-0 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Tidak Ada Data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-200 mt-auto">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Sisa Anggaran</p>
                                <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($sisaAnggaranPeralatan, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('anggaran.index') }}" class="text-sm text-blue-600 hover:underline">Kelola</a>
                        </div>
                        <a href="{{ route('peralatan.index') }}" class="block text-center text-blue-600 hover:underline font-semibold">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card Pemeliharaan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col transition hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pemeliharaan</h3>
                                <span class="text-sm text-gray-500">({{ $pemeliharaanCount }} Data)</span>
                            </div>
                            <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-4 flex-grow">
                        {{-- PERBAIKAN: Menambahkan judul kolom --}}
                        <div class="flex justify-between text-sm font-semibold text-gray-500 mb-2 border-b pb-2">
                            <span>Pekerjaan</span>
                            <span>Status</span>
                        </div>
                        <div class="space-y-3 mt-3">
                            @forelse ($pemeliharaan as $item)
                                <div class="flex justify-between items-center">
                                    <p class="text-sm text-gray-800 truncate pr-2">{{ $item->pekerjaan }}</p>
                                    <span class="flex-shrink-0 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Tidak Ada Data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-200 mt-auto">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Sisa Anggaran</p>
                                <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($sisaAnggaranPemeliharaan, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('anggaran.index') }}" class="text-sm text-blue-600 hover:underline">Kelola</a>
                        </div>
                        <a href="{{ route('pemeliharaan.index') }}" class="block text-center text-blue-600 hover:underline font-semibold">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
