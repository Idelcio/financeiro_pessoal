<x-app-layout>
    <x-slot:title>Editar Veículo</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('veiculos.show', $veiculo) }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
        <h1 class="text-2xl font-bold text-white">Editar: {{ $veiculo->nome }}</h1>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('veiculos.update', $veiculo) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-5">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Identificação</h2>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Apelido / Nome <span class="text-rose-400">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $veiculo->nome) }}" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    @error('nome') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Marca</label>
                        <input type="text" name="marca" value="{{ old('marca', $veiculo->marca) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Modelo</label>
                        <input type="text" name="modelo" value="{{ old('modelo', $veiculo->modelo) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Ano</label>
                        <input type="number" name="ano" value="{{ old('ano', $veiculo->ano) }}" min="1900" max="{{ now()->year + 1 }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Placa</label>
                        <input type="text" name="placa" value="{{ old('placa', $veiculo->placa) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Cor</label>
                        <input type="text" name="cor" value="{{ old('cor', $veiculo->cor) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">KM Atual</label>
                        <input type="number" name="km_atual" value="{{ old('km_atual', $veiculo->km_atual) }}" min="0"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo de Combustível <span class="text-rose-400">*</span></label>
                    <select name="tipo_combustivel" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        @foreach(['gasolina' => 'Gasolina', 'etanol' => 'Etanol', 'flex' => 'Flex (Gasolina/Etanol)', 'diesel' => 'Diesel', 'gnv' => 'GNV', 'hibrido' => 'Híbrido', 'eletrico' => 'Elétrico'] as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo_combustivel', $veiculo->tipo_combustivel) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm resize-none">{{ old('observacao', $veiculo->observacao) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $veiculo->ativo) ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-emerald-500 bg-slate-800 border-slate-600 focus:ring-emerald-500">
                    <label for="ativo" class="text-sm text-slate-300">Veículo ativo</label>
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
                <form method="POST" action="{{ route('veiculos.destroy', $veiculo) }}" class="ml-auto"
                    x-data x-on:submit.prevent="if(confirm('Remover este veículo e todos os dados relacionados?')) $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 bg-rose-900/30 hover:bg-rose-900/50 text-rose-400 font-semibold rounded-xl text-sm transition-colors">
                        Remover
                    </button>
                </form>
            </div>
        </form>
    </div>
</x-app-layout>
