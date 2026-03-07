<x-app-layout>
    <x-slot:title>Novo Veículo</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('veiculos.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
        <h1 class="text-2xl font-bold text-white">Novo Veículo</h1>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('veiculos.store') }}" class="space-y-5">
            @csrf

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-5">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Identificação</h2>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Apelido / Nome <span class="text-rose-400">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome') }}" required placeholder="Ex: Civic do trabalho"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    @error('nome') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Marca</label>
                        <input type="text" name="marca" value="{{ old('marca') }}" placeholder="Honda, Toyota..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Modelo</label>
                        <input type="text" name="modelo" value="{{ old('modelo') }}" placeholder="Civic, Corolla..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Ano</label>
                        <input type="number" name="ano" value="{{ old('ano') }}" placeholder="{{ now()->year }}" min="1900" max="{{ now()->year + 1 }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Placa</label>
                        <input type="text" name="placa" value="{{ old('placa') }}" placeholder="ABC-1234"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">Cor</label>
                        <input type="text" name="cor" value="{{ old('cor') }}" placeholder="Prata, Branco..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-1.5">KM Atual</label>
                        <input type="number" name="km_atual" value="{{ old('km_atual') }}" placeholder="0" min="0"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Tipo de Combustível <span class="text-rose-400">*</span></label>
                    <select name="tipo_combustivel" required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-emerald-500 text-sm">
                        <option value="gasolina" {{ old('tipo_combustivel') === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                        <option value="etanol" {{ old('tipo_combustivel') === 'etanol' ? 'selected' : '' }}>Etanol</option>
                        <option value="flex" {{ old('tipo_combustivel') === 'flex' ? 'selected' : '' }}>Flex (Gasolina/Etanol)</option>
                        <option value="diesel" {{ old('tipo_combustivel') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="gnv" {{ old('tipo_combustivel') === 'gnv' ? 'selected' : '' }}>GNV</option>
                        <option value="hibrido" {{ old('tipo_combustivel') === 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                        <option value="eletrico" {{ old('tipo_combustivel') === 'eletrico' ? 'selected' : '' }}>Elétrico</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1.5">Observação</label>
                    <textarea name="observacao" rows="2" placeholder="Informações adicionais..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 text-sm resize-none">{{ old('observacao') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm transition-colors">
                    Cadastrar Veículo
                </button>
                <a href="{{ route('veiculos.index') }}"
                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-sm transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
