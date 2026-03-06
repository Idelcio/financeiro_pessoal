<x-guest-layout>
    <x-slot:title>Criar conta</x-slot:title>

    <h2 class="text-2xl font-bold text-white mb-2">Criar conta gratuita</h2>
    <p class="text-slate-400 text-sm mb-8">Comece a controlar suas finanças agora mesmo</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nome completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('name') border-rose-500 @enderror"
                placeholder="Seu nome" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('email') border-rose-500 @enderror"
                placeholder="seu@email.com" required autocomplete="username">
            @error('email')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Senha</label>
            <input id="password" type="password" name="password"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors @error('password') border-rose-500 @enderror"
                placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
            @error('password')
                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirmar
                senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                placeholder="Repita a senha" required autocomplete="new-password">
        </div>

        <button type="submit"
            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-3 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">
            Criar minha conta
        </button>

        <p class="text-center text-sm text-slate-400">
            Já tem conta?
            <a href="{{ route('login') }}"
                class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">
                Fazer login
            </a>
        </p>
    </form>
</x-guest-layout>