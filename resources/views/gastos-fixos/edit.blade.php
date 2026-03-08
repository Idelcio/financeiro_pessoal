<x-app-layout>
    <x-slot name="title">Editar Gasto Fixo</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('gastos-fixos.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Editar: {{ $gasto->nome }}</h1>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('gastos-fixos.update', $gasto) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Nome da conta *</label>
                    <input type="text" name="nome" value="{{ old('nome', $gasto->nome) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                    @error('nome')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Tipo de gasto *</label>
                    <select name="tipo_gasto" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500">
                        @foreach(['luz' => 'Energia Eletrica', 'agua' => 'Agua', 'telefone' => 'Telefone / Internet', 'streaming' => 'Streaming', 'aluguel' => 'Aluguel', 'outro' => 'Outro'] as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo_gasto', $gasto->tipo_gasto) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Valor mensal (R$) *</label>
                        <input type="number" step="0.01" name="valor" value="{{ old('valor', number_format($gasto->valor_centavos / 100, 2, '.', '')) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Dia de vencimento *</label>
                        <input type="number" min="1" max="31" name="dia_vencimento" value="{{ old('dia_vencimento', $gasto->dia_vencimento) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Tipo *</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="pessoal" {{ old('tipo', $gasto->tipo) === 'pessoal' ? 'checked' : '' }} class="text-emerald-500">
                            <span class="text-sm text-white">Pessoal</span>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo" value="empresa" {{ old('tipo', $gasto->tipo) === 'empresa' ? 'checked' : '' }} class="text-blue-500">
                            <span class="text-sm text-white">Empresa</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-800 rounded-xl">
                    <input type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', $gasto->ativo) ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 rounded">
                    <label for="ativo" class="text-sm text-slate-300">Gasto ativo (aparece nas listagens)</label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Observacao</label>
                    <textarea name="observacao" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 resize-none">{{ old('observacao', $gasto->observacao) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('gastos-fixos.index') }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
