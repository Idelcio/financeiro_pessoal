<x-app-layout>
    <x-slot name="title">Nova Entrada</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('receitas.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Nova Entrada</h1>
                <p class="text-slate-400 text-sm">Registre um salário, freelance ou qualquer dinheiro recebido</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('receitas.store') }}" class="space-y-5"
                x-data="{ recorrente: {{ old('recorrente') ? 'true' : 'false' }} }">
                @csrf

                {{-- Toggle recorrente --}}
                <div class="flex items-center justify-between p-4 bg-slate-800 rounded-xl border border-slate-700">
                    <div>
                        <p class="text-sm font-medium text-white">Entrada recorrente</p>
                        <p class="text-xs text-slate-400 mt-0.5">Repete todo mês (salário, aluguel recebido, etc.)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                        <input type="checkbox" name="recorrente" value="1" x-model="recorrente" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-600 peer-checked:bg-emerald-500 rounded-full transition-colors"></div>
                        <div class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-transform peer-checked:translate-x-5"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Descrição *</label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}"
                        x-bind:placeholder="recorrente ? 'Ex: Salário, Aluguel recebido...' : 'Ex: Serviço de garçom, Freelance...'"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('descricao') border-rose-500 @enderror" required>
                    @error('descricao')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Valor (R$) *</label>
                        <input type="number" step="0.01" name="valor" value="{{ old('valor') }}" placeholder="0,00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">
                            <span x-show="!recorrente">Data do recebimento *</span>
                            <span x-show="recorrente" x-cloak>Data de início *</span>
                        </label>
                        <input type="date" name="data" value="{{ old('data', now()->toDateString()) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
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

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2" placeholder="Opcional..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 resize-none">{{ old('observacao') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('receitas.index') }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Registrar Entrada</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
