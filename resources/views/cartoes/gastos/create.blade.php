<x-app-layout>
    <x-slot name="title">Nova Compra - {{ $cartao->nome }}</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cartoes.show', $cartao) }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Nova Compra</h1>
                <p class="text-slate-400 text-sm">Cartão: {{ $cartao->nome }}</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('cartoes.gastos.store', $cartao) }}" class="space-y-5"
                x-data="{ recorrente: {{ old('recorrente') ? 'true' : 'false' }} }">
                @csrf

                {{-- Toggle recorrente --}}
                <div class="flex items-center justify-between p-4 bg-slate-800 rounded-xl border border-slate-700">
                    <div>
                        <p class="text-sm font-medium text-white">Compra recorrente</p>
                        <p class="text-xs text-slate-400 mt-0.5">Cobrado mensalmente sem data de encerramento (assinatura, mensalidade)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                        <input type="checkbox" name="recorrente" value="1" x-model="recorrente" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-600 peer-checked:bg-emerald-500 rounded-full transition-colors"></div>
                        <div class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-transform peer-checked:translate-x-5"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Descrição *</label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}" placeholder="Ex: Netflix, iPhone 15, Supermercado..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('descricao') border-rose-500 @enderror" required>
                    @error('descricao')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">
                            <span x-show="!recorrente">Valor total (R$) *</span>
                            <span x-show="recorrente" x-cloak>Valor mensal (R$) *</span>
                        </label>
                        <input type="number" step="0.01" name="valor_total" value="{{ old('valor_total') }}" placeholder="0,00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div x-show="!recorrente">
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Número de parcelas *</label>
                        <input type="number" min="1" max="120" name="total_parcelas" value="{{ old('total_parcelas', 1) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                        <p class="text-xs text-slate-500 mt-1">1 = à vista</p>
                    </div>
                    <div x-show="recorrente" x-cloak class="flex items-center">
                        <div class="flex items-center gap-2 px-4 py-3 bg-emerald-900/30 border border-emerald-800/50 rounded-xl w-full">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="text-xs text-emerald-400">Renovação automática todo mês</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        <span x-show="!recorrente">Data da compra *</span>
                        <span x-show="recorrente" x-cloak>Data de início *</span>
                    </label>
                    <input type="date" name="data_compra" value="{{ old('data_compra', now()->toDateString()) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Categoria</label>
                    <input type="text" name="categoria" value="{{ old('categoria') }}" placeholder="Ex: Streaming, Alimentação..."
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
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 resize-none">{{ old('observacao') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('cartoes.show', $cartao) }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">
                        <span x-show="!recorrente">Registrar Compra</span>
                        <span x-show="recorrente" x-cloak>Registrar Recorrente</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
