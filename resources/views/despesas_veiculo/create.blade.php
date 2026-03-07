<x-app-layout>
    <x-slot:title>Registrar Despesa</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('veiculos.show', $veiculo) }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ $veiculo->nome }}
        </a>
        <h1 class="text-2xl font-bold text-white">Registrar Despesa</h1>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('veiculos.despesas.store', $veiculo) }}" class="space-y-5">
            @csrf

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-5">

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo <span class="text-rose-400">*</span></label>
                    <select name="tipo" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        @foreach($tipos as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Descrição <span class="text-rose-400">*</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}" required
                        placeholder="Ex: Multa por excesso de velocidade"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    @error('descricao') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Data <span class="text-rose-400">*</span></label>
                        <input type="date" name="data" value="{{ old('data', now()->format('Y-m-d')) }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Valor (R$) <span class="text-rose-400">*</span></label>
                        <input type="number" name="valor" value="{{ old('valor') }}" step="0.01" min="0.01" required placeholder="0,00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                        @error('valor') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo de uso</label>
                    <select name="tipo_uso"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        <option value="pessoal" {{ old('tipo_uso') !== 'empresa' ? 'selected' : '' }}>Pessoal</option>
                        <option value="empresa" {{ old('tipo_uso') === 'empresa' ? 'selected' : '' }}>Empresa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm resize-none">{{ old('observacao') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm transition-colors">
                    Registrar Despesa
                </button>
                <a href="{{ route('veiculos.show', $veiculo) }}"
                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-sm transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
