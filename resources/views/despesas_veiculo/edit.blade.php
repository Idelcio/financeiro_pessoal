<x-app-layout>
    <x-slot:title>Editar Despesa</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('veiculos.show', $veiculo) }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ $veiculo->nome }}
        </a>
        <h1 class="text-2xl font-bold text-white">Editar Despesa</h1>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('veiculos.despesas.update', [$veiculo, $despesa]) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-5">

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo <span class="text-rose-400">*</span></label>
                    <select name="tipo" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        @foreach($tipos as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo', $despesa->tipo) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Descrição <span class="text-rose-400">*</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao', $despesa->descricao) }}" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Data <span class="text-rose-400">*</span></label>
                        <input type="date" name="data" value="{{ old('data', $despesa->data->format('Y-m-d')) }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Valor (R$) <span class="text-rose-400">*</span></label>
                        <input type="number" name="valor" value="{{ old('valor', $despesa->valor_centavos / 100) }}" step="0.01" min="0.01" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo de uso</label>
                    <select name="tipo_uso"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        <option value="pessoal" {{ old('tipo_uso', $despesa->tipo_uso) === 'pessoal' ? 'selected' : '' }}>Pessoal</option>
                        <option value="empresa" {{ old('tipo_uso', $despesa->tipo_uso) === 'empresa' ? 'selected' : '' }}>Empresa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm resize-none">{{ old('observacao', $despesa->observacao) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm transition-colors">
                    Salvar Alterações
                </button>
                <a href="{{ route('veiculos.show', $veiculo) }}"
                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-sm transition-colors">
                    Cancelar
                </a>
                <form method="POST" action="{{ route('veiculos.despesas.destroy', [$veiculo, $despesa]) }}" class="ml-auto"
                    x-data x-on:submit.prevent="if(confirm('Remover esta despesa?')) $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 bg-rose-900/30 hover:bg-rose-900/50 text-rose-400 font-semibold rounded-xl text-sm transition-colors">
                        Remover
                    </button>
                </form>
            </div>
        </form>
    </div>
</x-app-layout>
