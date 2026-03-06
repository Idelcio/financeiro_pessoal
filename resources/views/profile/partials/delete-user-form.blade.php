<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-white">
            Excluir Conta
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Depois de excluir sua conta, todos os recursos e dados serão deletados permanentemente. Antes de excluir,
            baixe todos os dados que você deseja manter.
        </p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-xl transition-colors focus:outline-none">Excluir
        Conta</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-slate-900">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white mb-2">
                Tem certeza que deseja excluir sua conta?
            </h2>

            <p class="text-sm text-slate-400 mb-6">
                Depois que a conta for excluída, todos os dados serão perdidos. Por favor, digite sua senha para
                confirmar.
            </p>

            <div>
                <label for="password" class="sr-only">Senha</label>
                <input id="password" name="password" type="password"
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors @error('password', 'userDeletion') border-rose-500 @enderror"
                    placeholder="Sua senha" />
                @error('password', 'userDeletion')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-medium rounded-xl transition-colors focus:outline-none">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-xl transition-colors focus:outline-none">
                    Excluir Conta
                </button>
            </div>
        </form>
    </x-modal>
</section>