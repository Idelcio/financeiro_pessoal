<x-guest-layout>
    <x-slot:title>Login</x-slot:title>

    <h2 class="text-2xl font-bold text-white mb-2">Bem-vindo de volta</h2>
    <p class="text-slate-400 text-sm mb-8">Entre na sua conta para acessar o painel</p>

    <x-auth-session-status class="mb-4 text-emerald-300 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('email') border-rose-500 @enderror"
                placeholder="seu@email.com" required autofocus autocomplete="username">
            @error('email')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Senha</label>
            <input id="password" type="password" name="password"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('password') border-rose-500 @enderror"
                placeholder="Sua senha" required autocomplete="current-password">
            @error('password')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-emerald-500 focus:ring-emerald-500">
                <span class="text-sm text-slate-400">Lembrar de mim</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors">
                    Esqueci a senha
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-3 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">
            Entrar
        </button>

        <p class="text-center text-sm text-slate-400">
            Não tem conta?
            <a href="{{ route('register') }}"
                class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">
                Criar conta gratuita
            </a>
        </p>
    </form>
</x-guest-layout>