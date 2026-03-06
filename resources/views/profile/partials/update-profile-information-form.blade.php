<section>
    <header>
        <h2 class="text-lg font-bold text-white">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Atualize as informações de perfil e o endereço de e-mail da sua conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nome</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                autocomplete="name"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('name') border-rose-500 @enderror">
            @error('name')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('email') border-rose-500 @enderror">
            @error('email')<p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-slate-300">
                        Seu e-mail ainda não foi verificado.

                        <button form="send-verification"
                            class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors focus:outline-none">
                            Clique aqui para reenviar o e-mail de verificação.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-emerald-400 font-medium">
                            Um novo link de verificação foi enviado para seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors focus:outline-none">
                Salvar
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400">Salvo.</p>
            @endif
        </div>
    </form>
</section>