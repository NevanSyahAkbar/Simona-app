<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modul Perlengkapan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('perlengkapan.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
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

                <form method="GET" action="{{ route('perlengkapan.index') }}" class="mb-4 sm:flex sm:space-x-4 sm:items-center">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mitra</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DPP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sinkronisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($perlengkapan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loop->iteration + $perlengkapan->firstItem() - 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs whitespace-normal break-words">{{ $item->pekerjaan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->mitra }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Rp {{ number_format($item->dpp, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('perlengkapan.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('perlengkapan.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                        @if(Auth::user()->role == 'admin')
                                        <form action="{{ route('perlengkapan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                        </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($item->sync)
                                            <button type="button" disabled class="bg-gray-300 text-gray-600 cursor-not-allowed py-1 px-3 rounded text-xs">
                                                Tersinkronisasi
                                            </button>
                                        @else
                                            <form action="{{ route('perlengkapan.kirimApi', $item->id) }}" method="POST">
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
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $perlengkapan->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- Letakkan ini di bagian paling bawah file create.blade.php --}}

@push('scripts')
<script>
// Pastikan skrip berjalan setelah semua elemen halaman dimuat
document.addEventListener('DOMContentLoaded', function() {

    // 1. Temukan form berdasarkan ID-nya.
    //    Pastikan Anda menambahkan id="perlengkapan-form" ke tag <form> Anda.
    const form = document.getElementById('perlengkapan-form');

    // Jika form tidak ditemukan, hentikan skrip untuk menghindari error
    if (!form) {
        console.error('Form dengan ID "perlengkapan-form" tidak ditemukan.');
        return;
    }

    // 2. Tambahkan event listener untuk event 'submit'
    form.addEventListener('submit', function(event) {
        // Mencegah form dikirim dengan cara tradisional (reload halaman)
        event.preventDefault();

        // Ambil URL tujuan dan metode dari atribut form
        const url = form.getAttribute('action');
        const method = form.getAttribute('method');

        // Kumpulkan semua data dari input form
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // 3. Kirim data menggunakan Fetch API
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
            // Cek dulu apakah respons dari server OK (status 2xx)
            if (!response.ok) {
                // Jika tidak OK (misal: error validasi 422), ubah respons menjadi JSON
                // lalu lemparkan sebagai error agar bisa ditangkap oleh .catch()
                return response.json().then(err => { throw err; });
            }
            // Jika OK, lanjutkan
            return response.json();
        })
        .then(result => {
            // 4. Tangani respons sukses dari server
            console.log('Sukses:', result);
            alert(result.message || 'Data berhasil disimpan!');

            // Ambil ID dari data yang baru dibuat
            const newId = result.data.id;

            if (newId) {
                // Redirect ke halaman detail data yang baru
                window.location.href = `/perlengkapan/${newId}`;
            } else {
                // Jika tidak ada ID, kembali ke halaman index
                window.location.href = '/perlengkapan';
            }
        })
        .catch(error => {
            // 5. Tangani jika terjadi error (koneksi gagal atau error validasi)
            console.error('Error:', error);

            // Tampilkan pesan error validasi di bawah input yang relevan
            if (error.errors) {
                // Hapus dulu pesan error lama
                document.querySelectorAll('.error-message').forEach(el => el.remove());

                // Tampilkan pesan error baru
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
