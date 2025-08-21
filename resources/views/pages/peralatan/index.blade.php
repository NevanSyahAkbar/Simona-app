<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modul Peralatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('peralatan.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    + Tambah Data
                </a>
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Form Pencarian dan Filter -->
                <form method="GET" action="{{ route('peralatan.index') }}" class="mb-4 sm:flex sm:space-x-4 sm:items-center">
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
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($peralatan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs whitespace-normal break-words">{{ $item->pekerjaan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->mitra }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Rp {{ number_format($item->dpp, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('peralatan.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('peralatan.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                        @if(Auth::user()->role == 'admin')
                                        <form action="{{ route('peralatan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                        </form>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- INI ADALAH LOGIKA UTAMANYA --}}
                                        @if ($item->sync)
                                            {{-- Jika SUDAH sinkron (nilai = 1), tampilkan tombol disabled --}}
                                            <button type="button" disabled style="background-color: #ccc; color: #666; cursor: not-allowed; padding: 8px 12px; border: none; border-radius: 5px;">
                                                Tersinkronisasi
                                            </button>
                                        @else
                                            {{-- Jika BELUM sinkron (nilai = 0), tampilkan form dengan tombol submit aktif --}}
                                            <form action="{{ route('peralatan.kirimApi', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" style="background-color: #007bff; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;">
                                                    kirim
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $peralatan->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // =========================================================================
    // BAGIAN BARU: Periksa localStorage saat halaman dimuat
    // Loop semua form dan nonaktifkan jika ID-nya sudah ada di catatan
    // =========================================================================
    document.querySelectorAll('form.kirim-form[data-id]').forEach(form => {
        const itemId = form.getAttribute('data-id');
        if (localStorage.getItem('item_terkirim_' + itemId)) {
            const tombol = form.querySelector('button');
            if (tombol) {
                tombol.disabled = true;
                tombol.innerHTML = 'Terkirim';
                form.onsubmit = (e) => e.preventDefault();
            }
        }
    });


    // =========================================================================
    // Kode Anda yang sudah ada, dengan sedikit modifikasi
    // =========================================================================
    const tombolKirimSimulasi = document.getElementById('tombolKirimSimulasi');
    const checkboxPilihSemua = document.getElementById('pilihSemua');
    const semuaCheckboxItem = document.querySelectorAll('.pilih-item');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!csrfToken) {
        console.error('CSRF Token meta tag not found!');
        return;
    }

    if (tombolKirimSimulasi) {
        // Fungsi perbaruiStatusTombol, event listener checkbox, dll. (tidak berubah)
        function perbaruiStatusTombol() { /* ... kode Anda ... */ }
        checkboxPilihSemua.addEventListener('change', function() { /* ... kode Anda ... */ });
        semuaCheckboxItem.forEach(checkbox => { /* ... kode Anda ... */ });

        // Event listener untuk tombol kirim massal
        tombolKirimSimulasi.addEventListener('click', function() {
            const idTerpilih = Array.from(document.querySelectorAll('.pilih-item:checked'))
                                    .map(cb => cb.getAttribute('data-id'));

            if (idTerpilih.length === 0 || !confirm(`Anda akan mengirim ${idTerpilih.length} data. Lanjutkan?`)) {
                return;
            }

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Mengirim...';

            fetch("{{ route('peralatan.kirimApi', $item->id) }}", { // Sesuaikan dengan route bulk Anda
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify({ ids: idTerpilih })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Proses berhasil dijalankan!');

                // ===== MODIFIKASI UTAMA: Simpan catatan ke localStorage =====
                idTerpilih.forEach(id => {
                    localStorage.setItem('item_terkirim_' + id, 'true');
                });
                // ===========================================================

                location.reload(); // Muat ulang halaman untuk melihat perubahan
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan.');
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });

        perbaruiStatusTombol();
    }
});
</script>
@endpush
    @endpush
</x-app-layout>
