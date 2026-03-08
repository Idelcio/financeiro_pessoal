<x-app-layout>
    <x-slot name="title">Novo Cartao</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cartoes.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Novo Cartao</h1>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('cartoes.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Nome do cartao *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Ex: Nubank, Inter, Bradesco..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('nome') border-rose-500 @enderror" required>
                        @error('nome')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Bandeira</label>
                        <select name="bandeira" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                            <option value="">Selecione...</option>
                            <option value="visa" {{ old('bandeira') === 'visa' ? 'selected' : '' }}>Visa</option>
                            <option value="mastercard" {{ old('bandeira') === 'mastercard' ? 'selected' : '' }}>Mastercard</option>
                            <option value="elo" {{ old('bandeira') === 'elo' ? 'selected' : '' }}>Elo</option>
                            <option value="amex" {{ old('bandeira') === 'amex' ? 'selected' : '' }}>American Express</option>
                            <option value="hipercard" {{ old('bandeira') === 'hipercard' ? 'selected' : '' }}>Hipercard</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Limite (R$)</label>
                        <input type="number" step="0.01" name="limite" value="{{ old('limite') }}" placeholder="Opcional"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Dia de fechamento</label>
                        <input type="number" min="1" max="31" name="dia_fechamento" value="{{ old('dia_fechamento') }}" placeholder="Ex: 22"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Dia de vencimento</label>
                        <input type="number" min="1" max="31" name="dia_vencimento" value="{{ old('dia_vencimento') }}" placeholder="Ex: 5"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Cor do cartao</label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach(['#6366f1' => 'Indigo', '#10b981' => 'Verde', '#3b82f6' => 'Azul', '#8b5cf6' => 'Roxo', '#f59e0b' => 'Amarelo', '#ef4444' => 'Vermelho', '#ec4899' => 'Rosa', '#64748b' => 'Cinza'] as $hex => $nome)
                            <label class="cursor-pointer" title="{{ $nome }}">
                                <input type="radio" name="cor" value="{{ $hex }}" {{ old('cor', '#6366f1') === $hex ? 'checked' : '' }} class="sr-only peer">
                                <span class="block w-8 h-8 rounded-full ring-2 ring-transparent peer-checked:ring-white peer-checked:ring-offset-2 peer-checked:ring-offset-slate-900 transition-all" style="background-color: {{ $hex }}"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Tipo *</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="pessoal" {{ old('tipo', 'pessoal') === 'pessoal' ? 'checked' : '' }} class="text-emerald-500">
                            <span class="text-sm text-white">Pessoal</span>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="empresa" {{ old('tipo') === 'empresa' ? 'checked' : '' }} class="text-blue-500">
                            <span class="text-sm text-white">Empresa</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('cartoes.index') }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
