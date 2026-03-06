<section>
    <header>
        <h2 class="text-lg font-bold text-white">
            Atualizar Senha
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Garanta que sua conta esteja segura usando uma senha longa e aleatória.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-slate-300 mb-1.5">Senha
                atual</label>
            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('current_password', 'updatePassword') border-rose-500 @enderror">
            @error('current_password', 'updatePassword')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-slate-300 mb-1.5">Nova
                senha</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('password', 'updatePassword') border-rose-500 @enderror">
            @error('password', 'updatePassword')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="block text-sm font-medium text-slate-300 mb-1.5">Confirmar nova senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('password_confirmation', 'updatePassword') border-rose-500 @enderror">
            @error('password_confirmation', 'updatePassword')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors focus:outline-none">
                Salvar
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400">Salvo.</p>
            @endif
        </div>
    </form>
</section>