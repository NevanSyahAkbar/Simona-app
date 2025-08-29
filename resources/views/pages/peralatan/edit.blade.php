<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Peralatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 text-gray-200 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Form ini akan mengirim data ke method 'update' di controller --}}
                <form action="{{ route('peralatan.update', $peralatan->id) }}" method="POST">
                    @method('PUT') {{-- Method spoofing untuk request UPDATE --}}

                    {{-- Menggunakan kembali form yang sama dengan halaman 'create' --}}
                    {{-- Variabel $peralatan dan $statuses dikirim dari controller --}}
                    @include('pages.peralatan._form')
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
