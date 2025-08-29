<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modul Pemeliharaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('pemeliharaan.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    + Tambah Data
                </a>
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                 @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif


                <!-- Form Pencarian dan Filter -->
                <form method="GET" action="{{ route('pemeliharaan.index') }}" class="mb-4 sm:flex sm:space-x-4 sm:items-center">
                    <input type="text" name="search" placeholder="Cari pekerjaan/mitra..." class="form-input rounded-md shadow-sm w-full sm:w-1/3 mb-2 sm:mb-0" value="{{ request('search') }}">
                    <select name="status" class="form-select rounded-md shadow-sm w-full sm:w-auto mb-2 sm:mb-0">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode pemeliharaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mitra</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DPP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sinkronisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pemeliharaan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loop->iteration + $pemeliharaan->firstItem() - 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs whitespace-normal break-words">{{ $item->kode_pemeliharaan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs whitespace-normal break-words">{{ $item->pekerjaan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->mitra }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Rp {{ number_format($item->dpp, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $item->status }}</span></td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('pemeliharaan.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>

                                        {{-- Tombol Edit selalu tampil --}}
                                        <a href="{{ route('pemeliharaan.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>

                                        @if (!$item->sync)
                                            {{-- Tombol Hapus hanya tampil jika belum sinkron --}}
                                            @if(Auth::user()->role == 'admin')
                                            <form action="{{ route('pemeliharaan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($item->sync)
                                            <span class="bg-gray-300 text-gray-600 py-1 px-3 rounded text-xs">
                                                Tersinkronisasi
                                            </span>
                                        @else
                                            <form action="{{ route('pemeliharaan.kirimApi', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-xs">
                                                    Kirim
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $pemeliharaan->links() }}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    // Pastikan skrip berjalan setelah semua elemen halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('pemeliharaan-form');

        if (!form) {
            // console.error('Form dengan ID "pemeliharaan-form" tidak ditemukan.');
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const url = form.getAttribute('action');
            const method = form.getAttribute('method');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // Penting agar Laravel mengembalikan JSON
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(result => {
                console.log('Sukses:', result);
                alert(result.message || 'Data berhasil disimpan!');

                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    window.location.href = '/pemeliharaan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    for (const field in error.errors) {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            const errorElement = document.createElement('p');
                            errorElement.className = 'text-red-500 text-xs mt-1 error-message';
                            errorElement.textContent = error.errors[field][0];
                            input.insertAdjacentElement('afterend', errorElement);
                        }
                    }
                    alert(error.message || 'Data yang Anda masukkan tidak valid.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
