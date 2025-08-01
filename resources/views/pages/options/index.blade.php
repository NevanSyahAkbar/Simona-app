<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Pilihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Berdasarkan Tipe -->
            <div class="mb-6">
                <form method="GET" action="{{ route('options.index') }}" class="flex items-center space-x-4">
                    <label for="type" class="text-gray-700 font-medium">Tampilkan Pilihan Untuk:</label>
                    <select name="type" id="type" onchange="this.form.submit()" class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @forelse($types as $type)
                            <option value="{{ $type }}" {{ $selectedType == $type ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $type)) }}
                            </option>
                        @empty
                            <option disabled>Belum ada tipe</option>
                        @endforelse
                    </select>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Terjadi Kesalahan</p>
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
                            {{ $optionToEdit ? 'Edit Data' : 'Tambah Data Baru' }}
                        </h3>

                        <form method="POST" action="{{ $optionToEdit ? route('options.update', $optionToEdit->id) : route('options.store') }}">
                            @csrf
                            @if($optionToEdit)
                                @method('PUT')
                            @endif

                            <div class="space-y-4">
                                <!-- Input Nama/Value -->
                                <div>
                                    <label for="value" class="block font-medium text-sm text-gray-700">Nama</label>
                                    <input type="text" name="value" id="value" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('value', $optionToEdit->value ?? '') }}" required autofocus>
                                </div>

                                <!-- Input Tipe (hanya untuk form tambah) -->
                                @if(!$optionToEdit)
                                    <div>
                                        <label for="type_form" class="block font-medium text-sm text-gray-700">Tambahkan ke Tipe</label>
                                        <select name="type" id="type_form" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                            <option value="">-- Pilih Tipe yang Ada --</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type }}" {{ $selectedType == $type ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="custom_type" class="block font-medium text-sm text-gray-700">Atau Buat Tipe Baru</label>
                                        <input type="text" name="custom_type" id="custom_type" placeholder="Contoh: jenis_dokumen" class="form-input rounded-md shadow-sm mt-1 block w-full">
                                    </div>
                                @else
                                    <input type="hidden" name="type" value="{{ $optionToEdit->type }}">
                                @endif
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                @if($optionToEdit)
                                <a href="{{ route('options.index', ['type' => $selectedType]) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                                @endif
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Tabel (Kanan) -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($options as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $options->firstItem() + $loop->index }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->value }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('options.index', ['type' => $selectedType, 'edit' => $item->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('options.destroy', $item->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                Data tidak ditemukan untuk tipe '{{ $selectedType }}'.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-gray-200">
                            {{ $options->appends(['type' => $selectedType])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
