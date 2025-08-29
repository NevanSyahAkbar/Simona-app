@csrf
{{-- Menampilkan error validasi --}}
@if ($errors->any())
    <div class="alert alert-danger mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <strong>Whoops!</strong> Terdapat beberapa masalah dengan input Anda.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Tahun -->
    <div>
        <label for="tahun" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tahun</label>
        <input type="number" name="tahun" id="tahun" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('tahun', $perlengkapan->tahun ?? date('Y')) }}" required />
    </div>

    <!-- Sub-Bagian dengan Tombol Kelola -->
    <div>
        <label for="sub_bagian" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Sub-Bagian</label>
        <div class="flex items-center space-x-2 mt-1">
            <select name="sub_bagian" id="sub_bagian" class="form-select rounded-md shadow-sm block w-full bg-gray-200 border-gray-300 text-black" required>
                <option value="">Pilih Sub-Bagian</option>
                @foreach($sub_bagians as $option)
                    <option value="{{ $option->value }}" {{ (old('sub_bagian', $perlengkapan->sub_bagian ?? '') == $option->value) ? 'selected' : '' }}>{{ $option->value }}</option>
                @endforeach
            </select>
            @if(Auth::check() && Auth::user()->role == 'admin')
            <button type="button" class="manage-options-btn flex-shrink-0 px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600" data-type="sub_bagian" data-title="Sub-Bagian" data-target-dropdown="#sub_bagian">
                Kelola
            </button>
            @endif
        </div>
    </div>

    <!-- Pekerjaan -->
    <div class="lg:col-span-3">
        <label for="pekerjaan" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pekerjaan</label>
        <input type="text" name="pekerjaan" id="pekerjaan" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('pekerjaan', $perlengkapan->pekerjaan ?? '') }}" required />
    </div>

    <!-- Baris Tanggal-tanggal -->
    <div>
        <label for="date_nd_user" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date ND User</label>
        <input type="date" name="date_nd_user" id="date_nd_user" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('date_nd_user', $perlengkapan->date_nd_user ?? '') }}" />
    </div>
    <div>
        <label for="date_survey" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date Survey</label>
        <input type="date" name="date_survey" id="date_survey" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('date_survey', $perlengkapan->date_survey ?? '') }}" />
    </div>
    <div>
        <label for="date_nd_ijin" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date ND Ijin</label>
        <input type="date" name="date_nd_ijin" id="date_nd_ijin" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('date_nd_ijin', $perlengkapan->date_nd_ijin ?? '') }}" />
    </div>
    <div>
        <label for="date_pr" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date PR</label>
        <input type="date" name="date_pr" id="date_pr" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('date_pr', $perlengkapan->date_pr ?? '') }}" />
    </div>
    <div>
        <label for="bast_user" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Bast User</label>
        <input type="date" name="bast_user" id="bast_user" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('bast_user', $perlengkapan->bast_user ?? '') }}" />
    </div>
    <div>
        <label for="nd_pembayaran" class="block font-medium text-sm text-gray-700 dark:text-gray-300">ND Pembayaran</label>
        <input type="date" name="nd_pembayaran" id="nd_pembayaran" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('nd_pembayaran', $perlengkapan->nd_pembayaran ?? '') }}" />
    </div>

    <!-- Baris Nomor PR, PO, GR -->
    <div>
        <label for="pr_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">PR</label>
        <input type="text" name="pr_number" id="pr_number" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('pr_number', $perlengkapan->pr_number ?? '') }}" />
    </div>
    <div>
        <label for="po_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">PO</label>
        <input type="text" name="po_number" id="po_number" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('po_number', $perlengkapan->po_number ?? '') }}" />
    </div>
    <div>
        <label for="gr_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">GR</label>
        <input type="text" name="gr_number" id="gr_number" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('gr_number', $perlengkapan->gr_number ?? '') }}" />
    </div>

    <!-- Baris Order PADI, Status, DPP -->
    <div>
        <label for="order_padi" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Order PADI</label>
        <div class="flex items-center space-x-2 mt-1">
            <select name="order_padi" id="order_padi" class="form-select rounded-md shadow-sm block w-full bg-gray-200 border-gray-300 text-black" required>
                <option value="">Pilih Order PADI</option>
                @foreach($order_padis as $option)
                    <option value="{{ $option->value }}" {{ (old('order_padi', $perlengkapan->order_padi ?? '') == $option->value) ? 'selected' : '' }}>{{ $option->value }}</option>
                @endforeach
            </select>
            @if(Auth::check() && Auth::user()->role == 'admin')
            <button type="button" class="manage-options-btn flex-shrink-0 px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600" data-type="order_padi" data-title="Order PADI" data-target-dropdown="#order_padi">
                Kelola
            </button>
            @endif
        </div>
    </div>
    <div>
        <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Status</label>
        <div class="flex items-center space-x-2 mt-1">
            <select name="status" id="status" class="form-select rounded-md shadow-sm block w-full bg-gray-200 border-gray-300 text-black" required>
                <option value="">Pilih Status</option>
                @foreach($statuses as $option)
                    <option value="{{ $option->value }}" {{ (old('status', $perlengkapan->status ?? '') == $option->value) ? 'selected' : '' }}>{{ $option->value }}</option>
                @endforeach
            </select>
            @if(Auth::check() && Auth::user()->role == 'admin')
            <button type="button" class="manage-options-btn flex-shrink-0 px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600" data-type="status" data-title="Status" data-target-dropdown="#status">
                Kelola
            </button>
            @endif
        </div>
    </div>
    <div>
        <label for="dpp" class="block font-medium text-sm text-gray-700 dark:text-gray-300">DPP</label>
        <input type="number" step="0.01" name="dpp" id="dpp" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('dpp', $perlengkapan->dpp ?? '') }}" required />
    </div>

    <!-- Mitra -->
    <div>
        <label for="mitra" class="block font-medium text-sm text-gray-700 dark:text-gray-300">MITRA</label>
        <input type="text" name="mitra" id="mitra" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" value="{{ old('mitra', $perlengkapan->mitra ?? '') }}" required />
    </div>

    <!-- Keterangan -->
    <div class="lg:col-span-3">
        <label for="keterangan" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
        <textarea name="keterangan" id="keterangan" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-200 border-gray-300 text-black" rows="3">{{ old('keterangan', $perlengkapan->keterangan ?? '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('perlengkapan.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mr-4">
        Batal
    </a>
    {{-- PERUBAHAN: Tombol diubah menjadi type="button" dan diberi ID --}}
    <button type="button" id="open-confirmation-modal-btn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Simpan Data
    </button>
</div>

<!-- =================================================================== -->
<!-- MODAL BARU UNTUK KONFIRMASI SIMPAN DATA -->
<!-- =================================================================== -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 text-center mb-4">Konfirmasi Data</h3>
            <div id="confirmation-summary" class="mt-2 px-7 py-3 text-sm text-gray-700 dark:text-gray-300 max-h-96 overflow-y-auto">
                {{-- Ringkasan data akan ditampilkan di sini oleh JavaScript --}}
            </div>
            <div class="items-center px-4 py-3 mt-4 flex justify-end space-x-4">
                <button id="cancel-confirmation-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                    Batal
                </button>
                <button id="confirm-save-btn" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none">
                    Konfirmasi & Simpan
                </button>
            </div>
        </div>
    </div>
</div>


<!-- =================================================================== -->
<!-- MODAL UNTUK KELOLA PILIHAN (DINAMIS) -->
<!-- =================================================================== -->
@if(Auth::check() && Auth::user()->role == 'admin')
<div id="options-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Kelola Pilihan</h3>

            <div class="mt-4 flex space-x-2">
                <input type="text" id="new-option-value" placeholder="Nama Pilihan Baru" class="form-input rounded-md shadow-sm block w-full">
                <button type="button" id="add-option-btn" class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">Tambah</button>
            </div>

            <div id="options-list-container" class="mt-4 max-h-60 overflow-y-auto text-left">
                <p class="text-gray-500">Memuat...</p>
            </div>

            <div class="items-center px-4 py-3 mt-4">
                <button id="close-modal-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =================================================
    // LOGIKA UNTUK MODAL KONFIRMASI SIMPAN (DIMODIFIKASI)
    // =================================================
    const openConfirmationBtn = document.getElementById('open-confirmation-modal-btn');
    const confirmationModal = document.getElementById('confirmation-modal');
    const summaryDiv = document.getElementById('confirmation-summary');
    const cancelConfirmationBtn = document.getElementById('cancel-confirmation-btn');
    const confirmSaveBtn = document.getElementById('confirm-save-btn');

    if (openConfirmationBtn) {
        const mainForm = openConfirmationBtn.closest('form');

        openConfirmationBtn.addEventListener('click', function() {
            // Logika untuk menampilkan modal (ini sudah benar, tidak perlu diubah)
            const formData = new FormData(mainForm);
            let summaryHtml = '<dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">';
            const labels = {
                tahun: 'Tahun', sub_bagian: 'Sub-Bagian', pekerjaan: 'Pekerjaan',
                date_nd_user: 'Date ND User', date_survey: 'Date Survey', date_nd_ijin: 'Date ND Ijin',
                date_pr: 'Date PR', bast_user: 'Bast User', nd_pembayaran: 'ND Pembayaran',
                pr_number: 'PR', po_number: 'PO', gr_number: 'GR',
                order_padi: 'Order PADI', status: 'Status', dpp: 'DPP',
                mitra: 'MITRA', keterangan: 'Keterangan'
            };
            for (const [key, value] of formData.entries()) {
                if (key === '_token' || key === '_method') continue;
                const label = labels[key] || key;
                const displayValue = value || '-';
                summaryHtml += `<dt class="font-semibold">${label}</dt><dd class="mb-2">${displayValue}</dd>`;
            }
            summaryHtml += '</dl>';
            summaryDiv.innerHTML = summaryHtml;
            confirmationModal.classList.remove('hidden');
        });

        cancelConfirmationBtn.addEventListener('click', function() {
            confirmationModal.classList.add('hidden');
        });

        // =======================================================
        // PERUBAHAN UTAMA DIMULAI DARI SINI
        // =======================================================
        confirmSaveBtn.addEventListener('click', async function() {
            // Ubah tampilan tombol untuk menunjukkan proses loading
            confirmSaveBtn.disabled = true;
            confirmSaveBtn.innerHTML = 'Menyimpan...';

            // Ambil data form
            const formData = new FormData(mainForm);
            // URL tujuan dari form Anda (action)
            const formActionUrl = mainForm.action;
            // Metode form (POST atau PUT/PATCH)
            const formMethod = mainForm.querySelector('input[name="_method"]')?.value || mainForm.method;


            try {
                // --- API Call #1: Simpan data ke server Laravel Anda ---
                const responseLaravel = await fetch(formActionUrl, {
                    method: 'POST', // Fetch selalu POST, metode asli (PUT/PATCH) dikirim dalam body
                    headers: {
                        'Accept': 'application/json', // Penting: minta response JSON dari Laravel
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                });

                const resultLaravel = await responseLaravel.json();

                // Jika validasi gagal (status 422) atau ada error server
                if (!responseLaravel.ok) {
                    // Tampilkan error dari Laravel (jika ada)
                    const errorMessage = resultLaravel.message || 'Terjadi kesalahan saat menyimpan data.';
                    throw new Error(errorMessage);
                }

                console.log('Sukses menyimpan ke Laravel:', resultLaravel.message);

                // --- API Call #2: Kirim data ke API lain (jika #1 sukses) ---
                console.log('Mengirim data ke API eksternal...');
                // Buat objek data bersih tanpa _token dan _method
                const dataUntukApiLain = Object.fromEntries(formData.entries());
                delete dataUntukApiLain['_token'];
                delete dataUntukApiLain['_method'];

                const responseEksternal = await fetch('URL_API_LAIN_YANG_DITUJU', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 'Authorization': 'Bearer YOUR_OTHER_API_TOKEN' // Jika perlu
                    },
                    body: JSON.stringify(dataUntukApiLain)
                });

                if (!responseEksternal.ok) {
                    // Jika API kedua gagal, proses tetap dianggap berhasil secara lokal
                    // tapi beri notifikasi khusus.
                    console.warn('Data berhasil disimpan, tapi gagal dikirim ke API eksternal.');
                    // Anda bisa memutuskan untuk tetap melanjutkan atau menampilkan error
                } else {
                    console.log('Sukses mengirim ke API eksternal.');
                }

                // Jika semua proses selesai, tampilkan notifikasi dan redirect
                alert('Data berhasil disimpan!');

                // Arahkan pengguna ke halaman index atau halaman lain dari response Laravel
                window.location.href = resultLaravel.redirect_url;

            } catch (error) {
                // Tangani semua jenis error di sini
                console.error('Error:', error);
                alert(`Gagal: ${error.message}`);

                // Kembalikan tombol ke keadaan semula jika gagal
                confirmSaveBtn.disabled = false;
                confirmSaveBtn.innerHTML = 'Konfirmasi & Simpan';
            }
        });
        // =======================================================
        // PERUBAHAN UTAMA BERAKHIR DI SINI
        // =======================================================
    }


    // =================================================
    // LOGIKA UNTUK MODAL KELOLA PILIHAN (ADMIN)
    // =================================================
    const optionsModal = document.getElementById('options-modal');
    if (!optionsModal) return;

    const openModalBtns = document.querySelectorAll('.manage-options-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const newOptionInput = document.getElementById('new-option-value');
    const optionsListContainer = document.getElementById('options-list-container');
    const modalTitle = document.getElementById('modal-title');
    const addOptionBtn = document.getElementById('add-option-btn');

    let currentManagement = {
        type: null,
        title: null,
        dropdownElement: null
    };

    const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;

    const openModal = (e) => {
        const btn = e.currentTarget;
        currentManagement.type = btn.dataset.type;
        currentManagement.title = btn.dataset.title;
        currentManagement.dropdownElement = document.querySelector(btn.dataset.targetDropdown);

        modalTitle.textContent = `Kelola Pilihan ${currentManagement.title}`;
        newOptionInput.placeholder = `Nama ${currentManagement.title} Baru`;
        optionsModal.classList.remove('hidden');
        loadOptions();
    };

    const closeModal = () => {
        optionsModal.classList.add('hidden');
    };

    openModalBtns.forEach(btn => btn.addEventListener('click', openModal));
    closeModalBtn.addEventListener('click', closeModal);

    async function loadOptions() {
        optionsListContainer.innerHTML = '<p class="text-gray-500">Memuat...</p>';
        try {
            const response = await fetch(`{{ url('options') }}?type=${currentManagement.type}`, {
                headers: { 'Accept': 'application/json' }
            });

            if (response.ok) {
                const options = await response.json();
                renderOptions(options);
                updateMainDropdown(options);
            } else {
                optionsListContainer.innerHTML = `<p class="text-red-500">Error: Gagal memuat data.</p>`;
            }
        } catch (error) {
            optionsListContainer.innerHTML = '<p class="text-red-500">Error Jaringan.</p>';
        }
    }

    function renderOptions(options) {
        optionsListContainer.innerHTML = '';
        if (options.length === 0) {
            optionsListContainer.innerHTML = '<p class="text-gray-500">Belum ada pilihan.</p>';
            return;
        }
        const list = document.createElement('ul');
        list.className = 'divide-y dark:divide-gray-600';
        options.forEach(option => {
            const listItem = document.createElement('li');
            listItem.className = 'p-2 flex justify-between items-center';
            listItem.innerHTML = `
                <span class="text-gray-800 dark:text-gray-200">${option.value}</span>
                <button data-id="${option.id}" class="delete-option-btn text-red-500 hover:text-red-700 text-sm">Hapus</button>
            `;
            list.appendChild(listItem);
        });
        optionsListContainer.appendChild(list);
    }

    function updateMainDropdown(options) {
        const dropdown = currentManagement.dropdownElement;
        if (!dropdown) return;

        const selectedValue = dropdown.value;
        dropdown.innerHTML = `<option value="">Pilih ${currentManagement.title}</option>`;
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.value;
            if (option.value === selectedValue) {
                optionElement.selected = true;
            }
            dropdown.appendChild(optionElement);
        });
    }

    addOptionBtn.addEventListener('click', async function () {
        const newValue = newOptionInput.value.trim();
        if (!newValue) return;

        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            alert('Error Keamanan: CSRF Token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
            return;
        }

        try {
            const response = await fetch("{{ route('options.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    type: currentManagement.type,
                    value: newValue
                })
            });
            if (response.ok) {
                newOptionInput.value = '';
                loadOptions();
            } else if (response.status === 419) {
                alert('Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.');
            } else {
                const errorData = await response.json();
                alert(errorData.message || 'Gagal menambahkan pilihan.');
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            alert('Terjadi kesalahan jaringan. Pastikan Anda terhubung ke internet dan coba lagi.');
        }
    });

    optionsListContainer.addEventListener('click', async function(e) {
        if (e.target && e.target.classList.contains('delete-option-btn')) {
            const optionId = e.target.dataset.id;

            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                alert('Error Keamanan: CSRF Token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menghapus pilihan ini?')) {
                try {
                    const response = await fetch(`{{ url('options') }}/${optionId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        loadOptions();
                    } else {
                        alert(`Gagal menghapus pilihan.`);
                    }
                } catch (error) {
                    alert('Terjadi kesalahan jaringan.');
                }
            }
        }
    });
});
</script>
