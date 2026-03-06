<x-app-layout>
    @slot('title')Editar {{ $imposto->nome }}@endslot

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('impostos.show', $imposto) }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Editar Imposto</h1>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('impostos.update', $imposto) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Tipo</label>
                        <div class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 text-sm uppercase font-bold">{{ $imposto->tipo }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Ano</label>
                        <div class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 text-sm">{{ $imposto->ano }}</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Nome / Identificacao *</label>
                    <input type="text" name="nome" value="{{ old('nome', $imposto->nome) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('nome') border-rose-500 @enderror" required>
                    @error('nome')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Descricao do Bem</label>
                    <input type="text" name="descricao_bem" value="{{ old('descricao_bem', $imposto->descricao_bem) }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Uso *</label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo_uso" value="pessoal" {{ old('tipo_uso', $imposto->tipo_uso) === 'pessoal' ? 'checked' : '' }} class="text-emerald-500">
                            <span class="text-sm text-white">Pessoal</span>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-3 bg-slate-800 border border-slate-700 rounded-xl cursor-pointer">
                            <input type="radio" name="tipo_uso" value="empresa" {{ old('tipo_uso', $imposto->tipo_uso) === 'empresa' ? 'checked' : '' }} class="text-blue-500">
                            <span class="text-sm text-white">Empresa</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Observacao</label>
                    <textarea name="observacao" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 resize-none">{{ old('observacao', $imposto->observacao) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('impostos.show', $imposto) }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white text-center rounded-xl transition-colors text-sm font-medium">Cancelar</a>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
