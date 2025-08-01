<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('img/logo_simona.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- PERUBAHAN: Latar belakang biru dipindahkan ke body --}}
    <body class="overflow-hidden bg-[#65C8FF]">
        <div class="font-sans text-gray-900 antialiased">
            <div class="min-h-screen flex">
                <!-- Kolom Kiri (Ilustrasi) -->
                <div class="hidden lg:flex w-1/2 items-center justify-center p-12">
                    <div>
                        <img src="{{ asset('img/ilustrasi.png') }}" alt="Ilustrasi Pelabuhan" class="max-w-md rounded-lg">
                        {{-- PERUBAHAN: Warna teks diubah menjadi putih agar terlihat --}}
                        <h2 class="mt-8 text-3xl font-bold text-center text-white">Sistem Monitoring Administrasi</h2>
                        <p class="mt-2 text-center text-blue-100">Manajemen data terpusat untuk operasional Administrasi</p>
                    </div>
                </div>

                <!-- Kolom Kanan (Konten Form) -->
                {{-- PERUBAHAN: Diberi background putih dan sudut kiri yang melengkung --}}
                <div class="w-full lg:w-1/2 flex items-center justify-center bg-white rounded-l-[60px]">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
