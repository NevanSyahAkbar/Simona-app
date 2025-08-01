<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- ======================================================================= --}}
            {{-- BAGIAN 1: INFORMASI PROFIL & AVATAR (Jika Jetstream Profile Photos aktif) --}}
            {{-- ======================================================================= --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg mb-8">
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            {{-- ======================================================================= --}}
            {{-- BAGIAN 2: UBAH PASSWORD                                                 --}}
            {{-- ======================================================================= --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg mb-8">
                    @livewire('profile.update-password-form')
                </div>
            @endif

            {{-- ======================================================================= --}}
            {{-- BAGIAN 3: AUTENTIKASI DUA FAKTOR (2FA)                                  --}}
            {{-- ======================================================================= --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg mb-8">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            {{-- ======================================================================= --}}
            {{-- BAGIAN 4: SESI BROWSER AKTIF                                            --}}
            {{-- ======================================================================= --}}
            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg mb-8">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- ======================================================================= --}}
            {{-- BAGIAN 5: HAPUS AKUN (ZONA BERBAHAYA)                                   --}}
            {{-- ======================================================================= --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="p-6 sm:p-8 bg-red-50 border-l-4 border-red-400 shadow sm:rounded-lg">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
