<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black-800 dark:text-black-200 leading-tight">
            {{ __('Tambah Data Perlengkapan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('perlengkapan.store') }}" method="POST">
                    {{-- Variabel $perlengkapan tidak didefinisikan di sini, jadi form akan kosong --}}
                    @include('pages.perlengkapan._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
