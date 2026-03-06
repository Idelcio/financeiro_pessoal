<x-app-layout>
    @slot('title')Registrar Abastecimento@endslot

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('combustivel.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Registrar Abastecimento</h1>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('combustivel.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Data *</label>
                        <input type="date" name="data_abastecimento" value="{{ old('data_abastecimento', now()->toDateString()) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Combustivel *</label>
                        <select name="tipo_combustivel" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                            <option value="gasolina" {{ old('tipo_combustivel', 'gasolina') === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                            <option value="etanol" {{ old('tipo_combustivel') === 'etanol' ? 'selected' : '' }}>Etanol</option>
                            <option value="diesel" {{ old('tipo_combustivel') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="gnv" {{ old('tipo_combustivel') === 'gnv' ? 'selected' : '' }}>GNV</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Valor total (R$) *</label>
                        <input type="number" step="0.01" name="valor_total" value="{{ old('valor_total') }}" placeholder="0,00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Litros</label>
                        <input type="number" step="0.001" name="litros" value="{{ old('litros') }}" placeholder="Ex: 40.5"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Preco por litro (R$)</label>
                        <input type="number" step="0.001" name="valor_litro" value="{{ old('valor_litro') }}" placeholder="Ex: 5.89"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">KM atual</label>
                        <input type="number" name="km_atual" value="{{ old('km_atual') }}" placeholder="Ex: 45000"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Posto</label>
                    <input type="text" name="posto" value="{{ old('posto') }}" placeholder="Nome do posto (opcional)"
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

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('combustivel.index') }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
