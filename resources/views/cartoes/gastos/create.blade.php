<x-app-layout>
    <x-slot name="title">Nova Compra - {{ $cartao->nome }}</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cartoes.show', $cartao) }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Nova Compra</h1>
                <p class="text-slate-400 text-sm">Cartao: {{ $cartao->nome }}</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('cartoes.gastos.store', $cartao) }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Descricao *</label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}" placeholder="Ex: iPhone 15, Supermercado..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('descricao') border-rose-500 @enderror" required>
                    @error('descricao')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Valor total (R$) *</label>
                        <input type="number" step="0.01" name="valor_total" value="{{ old('valor_total') }}" placeholder="0,00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Numero de parcelas *</label>
                        <input type="number" min="1" max="120" name="total_parcelas" value="{{ old('total_parcelas', 1) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                        <p class="text-xs text-slate-500 mt-1">1 = a vista</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Data da compra *</label>
                    <input type="date" name="data_compra" value="{{ old('data_compra', now()->toDateString()) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Categoria</label>
                    <input type="text" name="categoria" value="{{ old('categoria') }}" placeholder="Ex: Eletronicos, Alimentacao..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
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

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Observacao</label>
                    <textarea name="observacao" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 resize-none">{{ old('observacao') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('cartoes.show', $cartao) }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Registrar Compra</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
