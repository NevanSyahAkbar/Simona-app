<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Anggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom Form (Kiri) -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $anggaranToEdit ? 'Edit Anggaran' : 'Tambah Anggaran Baru' }}
                        </h3>

                        {{-- Form ini dinamis, bisa untuk 'store' (simpan baru) atau 'update' (perbarui) --}}
                        <form method="POST" action="{{ $anggaranToEdit ? route('anggaran.update', $anggaranToEdit->id) : route('anggaran.store') }}">
                            @csrf
                            @if($anggaranToEdit)
                                @method('PUT')
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
                                    {{-- Jika sedang mengedit, field ini tidak bisa diubah --}}
                                    <input type="number" name="tahun" id="tahun" class="form-input rounded-md shadow-sm mt-1 block w-full {{ $anggaranToEdit ? 'bg-gray-200 cursor-not-allowed' : '' }}" value="{{ old('tahun', $anggaranToEdit->tahun ?? date('Y')) }}" required {{ $anggaranToEdit ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label for="modul" class="block font-medium text-sm text-gray-700">Modul</label>
                                    {{-- Jika sedang mengedit, field ini tidak bisa diubah --}}
                                    <select name="modul" id="modul" class="form-select rounded-md shadow-sm mt-1 block w-full {{ $anggaranToEdit ? 'bg-gray-200 cursor-not-allowed' : '' }}" {{ $anggaranToEdit ? 'disabled' : '' }}>
                                        <option value="perlengkapan" @if(old('modul', $anggaranToEdit->modul ?? '') == 'perlengkapan') selected @endif>Perlengkapan</option>
                                        <option value="peralatan" @if(old('modul', $anggaranToEdit->modul ?? '') == 'peralatan') selected @endif>Peralatan</option>
                                        <option value="pemeliharaan" @if(old('modul', $anggaranToEdit->modul ?? '') == 'pemeliharaan') selected @endif>Pemeliharaan</option>
                                    </select>
                                    {{-- Hidden input untuk mengirim data modul saat disabled --}}
                                    @if($anggaranToEdit)
                                        <input type="hidden" name="modul" value="{{ $anggaranToEdit->modul }}">
                                    @endif
                                </div>
                                <div>
                                    <label for="total_anggaran" class="block font-medium text-sm text-gray-700">Total Anggaran (Rp)</label>
                                    <input type="number" name="total_anggaran" id="total_anggaran" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('total_anggaran', $anggaranToEdit->total_anggaran ?? '') }}" required placeholder="Contoh: 50000000">
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                @if($anggaranToEdit)
                                    <a href="{{ route('anggaran.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                                @endif
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Tabel (Kanan) -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Daftar Anggaran Tersimpan
                        </h3>
                        <div class="space-y-6">
                            @forelse($anggarans as $tahun => $items)
                                <div>
                                    <h4 class="font-semibold text-gray-800">Tahun {{ $tahun }}</h4>
                                    <div class="overflow-x-auto mt-2">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modul</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Anggaran</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucwords($item->modul) }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->total_anggaran, 2, ',', '.') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                            <a href="{{ route('anggaran.index', ['edit' => $item->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                            <form action="{{ route('anggaran.destroy', $item->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggaran ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Belum ada data anggaran yang disimpan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
