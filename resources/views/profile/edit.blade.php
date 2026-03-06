<x-app-layout>
    <x-slot:title>Meu Perfil</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Meu Perfil</h1>
            <p class="text-slate-400 text-sm mt-1">Gerencie suas informações e segurança da conta</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-slate-900 border border-rose-900/40 p-6 sm:p-8 rounded-2xl md:col-span-2 lg:col-span-1">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>