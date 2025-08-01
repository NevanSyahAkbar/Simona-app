<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
        <img src="{{ asset('img/pelindo.png') }}" alt="Logo Simona" class="w-24 h-auto" {{ $attributes }}>

    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/35 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

