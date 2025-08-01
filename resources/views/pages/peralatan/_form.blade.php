@csrf
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Tahun -->
   <div>
        <label for="tahun" class="block font-medium text-sm text-white">Tahun</label>
        <input type="number" name="tahun" id="tahun" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('tahun', $peralatan->tahun ?? date('Y')) }}" required />
    </div>

    <!-- Pekerjaan -->
    <div class="lg:col-span-2">
        <label for="pekerjaan" class="block font-medium text-sm text-white">Pekerjaan</label>
        <input type="text" name="pekerjaan" id="pekerjaan" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('pekerjaan', $peralatan->pekerjaan ?? '') }}" required />
    </div>

    <!-- ND Ijin -->
    <div>
        <label for="nd_ijin" class="block font-medium text-sm text-white">ND Ijin</label>
        <input type="date" name="nd_ijin" id="nd_ijin" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('nd_ijin', $peralatan->nd_ijin ?? '') }}" />
    </div>

    <!-- Date PR -->
    <div>
        <label for="date_pr" class="block font-medium text-sm text-white">Date PR</label>
        <input type="date" name="date_pr" id="date_pr" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('date_pr', $peralatan->date_pr ?? '') }}" />
    </div>

    <!-- PR Number -->
    <div>
        <label for="pr_number" class="block font-medium text-sm text-white">PR</label>
        <input type="text" name="pr_number" id="pr_number" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('pr_number', $peralatan->pr_number ?? '') }}" />
    </div>

    <!-- PO Number -->
    <div>
        <label for="po_number" class="block font-medium text-sm text-white">PO</label>
        <input type="text" name="po_number" id="po_number" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('po_number', $peralatan->po_number ?? '') }}" />
    </div>

    <!-- GR String -->
    <div>
        <label for="gr_string" class="block font-medium text-sm text-white">GR</label>
        <input type="text" name="gr_string" id="gr_string" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('gr_string', $peralatan->gr_string ?? '') }}" />
    </div>

    <!-- ND Pembayaran -->
    <div>
        <label for="nd_pembayaran" class="block font-medium text-sm text-white">ND Pembayaran</label>
        <input type="date" name="nd_pembayaran" id="nd_pembayaran" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('nd_pembayaran', $peralatan->nd_pembayaran ?? '') }}" />
    </div>

    <!-- DPP -->
    <div>
        <label for="dpp" class="block font-medium text-sm text-white">DPP</label>
        <input type="number" step="0.01" name="dpp" id="dpp" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('dpp', $peralatan->dpp ?? '') }}" required />
    </div>

    <!-- Mitra -->
    <div>
        <label for="mitra" class="block font-medium text-sm text-white">Mitra</label>
        <input type="text" name="mitra" id="mitra" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('mitra', $peralatan->mitra ?? '') }}" required />
    </div>

    <!-- Status dengan Tombol Kelola -->
    <div>
        <label for="status" class="block font-medium text-sm text-white">Status</label>
        <div class="flex items-center space-x-2 mt-1">
            <select name="status" id="status" class="form-select rounded-md shadow-sm block w-full" required>
                <option value="">Pilih Status</option>
                @foreach($statuses as $option)
                    <option value="{{ $option->value }}" {{ (old('status', $peralatan->status ?? '') == $option->value) ? 'selected' : '' }}>{{ $option->value }}</option>
                @endforeach
            </select>
            @if(Auth::check() && Auth::user()->role == 'admin')
            <button type="button" class="manage-options-btn flex-shrink-0 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700" data-type="status_peralatan" data-title="Status Peralatan" data-target-dropdown="#status">
                Kelola
            </button>
            @endif
        </div>
    </div>

    <!-- Keterangan -->
    <div class="lg:col-span-3">
        <label for="keterangan" class="block font-medium text-sm text-white">Keterangan</label>
        <textarea name="keterangan" id="keterangan" class="form-input rounded-md shadow-sm mt-1 block w-full" rows="3">{{ old('keterangan', $peralatan->keterangan ?? '') }}</textarea>
    </div>
</div>
<div class="flex items-center justify-end mt-6">
    <a href="{{ route('peralatan.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <button type="button" id="open-confirmation-modal-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Simpan Data</button>
</div>

<!-- =================================================================== -->
<!-- MODAL BARU UNTUK KONFIRMASI SIMPAN DATA -->
<!-- =================================================================== -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4">Konfirmasi Data</h3>
            <div id="confirmation-summary" class="mt-2 px-7 py-3 text-sm text-gray-700 max-h-96 overflow-y-auto">
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
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">Kelola Pilihan</h3>
            <div class="mt-4 flex space-x-2">
                <input type="text" id="new-option-value" placeholder="Nama Pilihan Baru" class="form-input rounded-md shadow-sm block w-full">
                <button type="button" id="add-option-btn" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">Tambah</button>
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
    // LOGIKA UNTUK MODAL KONFIRMASI SIMPAN
    // =================================================
    const openConfirmationBtn = document.getElementById('open-confirmation-modal-btn');
    const confirmationModal = document.getElementById('confirmation-modal');
    const summaryDiv = document.getElementById('confirmation-summary');
    const cancelConfirmationBtn = document.getElementById('cancel-confirmation-btn');
    const confirmSaveBtn = document.getElementById('confirm-save-btn');

    if (openConfirmationBtn) {
        const mainForm = openConfirmationBtn.closest('form');
        openConfirmationBtn.addEventListener('click', function() {
            const formData = new FormData(mainForm);
            let summaryHtml = '<dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">';
            const labels = {
                tahun: 'Tahun',
                pekerjaan: 'Pekerjaan',
                nd_ijin: 'ND Ijin',
                date_pr: 'Date PR',
                pr_number: 'PR',
                po_number: 'PO',
                gr_string: 'GR',
                nd_pembayaran: 'ND Pembayaran',
                dpp: 'DPP',
                mitra: 'Mitra',
                status: 'Status',
                keterangan: 'Keterangan'
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
        cancelConfirmationBtn.addEventListener('click', () => confirmationModal.classList.add('hidden'));
        confirmSaveBtn.addEventListener('click', () => mainForm.submit());
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
    let currentManagement = { type: null, title: null, dropdownElement: null };
    const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;

    const openModal = (e) => {
        const btn = e.currentTarget;
        currentManagement = {
            type: btn.dataset.type,
            title: btn.dataset.title,
            dropdownElement: document.querySelector(btn.dataset.targetDropdown)
        };
        modalTitle.textContent = `Kelola Pilihan ${currentManagement.title}`;
        newOptionInput.placeholder = `Nama ${currentManagement.title} Baru`;
        optionsModal.classList.remove('hidden');
        loadOptions();
    };

    const closeModal = () => optionsModal.classList.add('hidden');
    openModalBtns.forEach(btn => btn.addEventListener('click', openModal));
    closeModalBtn.addEventListener('click', closeModal);

    async function loadOptions() {
        optionsListContainer.innerHTML = '<p class="text-gray-500">Memuat...</p>';
        try {
            const response = await fetch(`{{ url('options') }}?type=${currentManagement.type}`, { headers: { 'Accept': 'application/json' } });
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
        list.className = 'divide-y divide-gray-200';
        options.forEach(option => {
            const listItem = document.createElement('li');
            listItem.className = 'p-2 flex justify-between items-center';
            listItem.innerHTML = `<span class="text-gray-800">${option.value}</span><button data-id="${option.id}" class="delete-option-btn text-red-500 hover:text-red-700 text-sm">Hapus</button>`;
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
            if (option.value === selectedValue) optionElement.selected = true;
            dropdown.appendChild(optionElement);
        });
    }

    async function addOption() {
        const newValue = newOptionInput.value.trim();
        if (!newValue) return;
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            alert('Error Keamanan: CSRF Token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        try {
            const response = await fetch("{{ route('options.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ type: currentManagement.type, value: newValue })
            });
            if (response.ok) {
                newOptionInput.value = '';
                loadOptions();
            } else if (response.status === 419) {
                alert('Sesi Anda telah berakhir. Silakan refresh halaman.');
            } else {
                const errorData = await response.json();
                alert(errorData.message || 'Gagal menambahkan pilihan.');
            }
        } catch (error) {
            alert('Terjadi kesalahan jaringan.');
        }
    }

    addOptionBtn.addEventListener('click', addOption);

    optionsListContainer.addEventListener('click', async (e) => {
        if (e.target && e.target.classList.contains('delete-option-btn')) {
            const optionId = e.target.dataset.id;
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                alert('Error Keamanan: CSRF Token tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            if (confirm('Apakah Anda yakin ingin menghapus pilihan ini?')) {
                try {
                    const response = await fetch(`{{ url('options') }}/${optionId}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
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
