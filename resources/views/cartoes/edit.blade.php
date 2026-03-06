<x-app-layout>
    @slot('title')Editar {{ $cartao->nome }}@endslot

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cartoes.show', $cartao) }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Editar Cartao</h1>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('cartoes.update', $cartao) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Nome do cartao *</label>
                    <input type="text" name="nome" value="{{ old('nome', $cartao->nome) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('nome') border-rose-500 @enderror" required>
                    @error('nome')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Bandeira</label>
                        <select name="bandeira" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                            <option value="">Selecione</option>
                            <option value="Visa" {{ old('bandeira', $cartao->bandeira) === 'Visa' ? 'selected' : '' }}>Visa</option>
                            <option value="Mastercard" {{ old('bandeira', $cartao->bandeira) === 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                            <option value="Elo" {{ old('bandeira', $cartao->bandeira) === 'Elo' ? 'selected' : '' }}>Elo</option>
                            <option value="American Express" {{ old('bandeira', $cartao->bandeira) === 'American Express' ? 'selected' : '' }}>American Express</option>
                            <option value="Hipercard" {{ old('bandeira', $cartao->bandeira) === 'Hipercard' ? 'selected' : '' }}>Hipercard</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Limite (R$)</label>
                        <input type="number" step="0.01" name="limite" value="{{ old('limite', $cartao->limite_centavos ? number_format($cartao->limite_centavos / 100, 2, '.', '') : '') }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Dia de fechamento</label>
                        <input type="number" min="1" max="31" name="dia_fechamento" value="{{ old('dia_fechamento', $cartao->dia_fechamento) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Dia de vencimento</label>
                        <input type="number" min="1" max="31" name="dia_vencimento" value="{{ old('dia_vencimento', $cartao->dia_vencimento) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Cor *</label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach(['#6366f1','#8b5cf6','#ec4899','#ef4444','#f97316','#eab308','#22c55e','#14b8a6','#3b82f6','#64748b'] as $cor)
                            <label class="cursor-pointer">
                                <input type="radio" name="cor" value="{{ $cor }}" {{ old('cor', $cartao->cor) === $cor ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-8 h-8 rounded-full peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-offset-slate-900 peer-checked:ring-white transition-all" style="background-color: {{ $cor }}"></div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Tipo *</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="pessoal" {{ old('tipo', $cartao->tipo) === 'pessoal' ? 'checked' : '' }} class="text-emerald-500">
                            <span class="text-sm text-white">Pessoal</span>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="empresa" {{ old('tipo', $cartao->tipo) === 'empresa' ? 'checked' : '' }} class="text-blue-500">
                            <span class="text-sm text-white">Empresa</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $cartao->ativo) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500">
                    <label for="ativo" class="text-sm text-white cursor-pointer">Cartao ativo</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('cartoes.show', $cartao) }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
