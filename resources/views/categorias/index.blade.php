<x-app-layout>
    <x-slot:title>Categorias</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Categorias</h1>
            <p class="text-slate-400 text-sm mt-1">Organize seus gastos avulsos por categoria</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Formulario nova categoria -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-base font-semibold text-white mb-4">Nova Categoria</h2>
            <form method="POST" action="{{ route('categorias.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Ex: Saúde, Lazer, Educação..."
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 @error('nome') border-rose-500 @enderror"
                        required>
                    @error('nome')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Cor *</label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach(['#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#3b82f6', '#64748b'] as $cor)
                            <label class="cursor-pointer">
                                <input type="radio" name="cor" value="{{ $cor }}" {{ old('cor', '#6366f1') === $cor ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-8 h-8 rounded-full peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-offset-slate-900 peer-checked:ring-white transition-all"
                                    style="background-color: {{ $cor }}"></div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Criar
                    Categoria</button>
            </form>
        </div>

        <!-- Lista de categorias -->
        <div class="space-y-3">
            @if($categorias->isEmpty())
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center">
                    <p class="text-slate-500">Nenhuma categoria criada ainda.</p>
                </div>
            @else
                @foreach($categorias as $categoria)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4" x-data="{ editando: false }">
                        <div x-show="!editando" class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $categoria->cor }}">
                            </div>
                            <span class="font-medium text-white flex-1">{{ $categoria->nome }}</span>
                            <span class="text-xs text-slate-500">{{ $categoria->gastos_avulsos_count }} gastos</span>
                            <button @click="editando = true" class="text-slate-500 hover:text-white transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('categorias.destroy', $categoria) }}" x-data
                                x-on:submit.prevent="if(confirm('Remover esta categoria?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-rose-400 transition-colors p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div x-show="editando" x-cloak>
                            <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="space-y-3">
                                @csrf @method('PATCH')
                                <input type="text" name="nome" value="{{ $categoria->nome }}"
                                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500"
                                    required>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach(['#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#3b82f6', '#64748b'] as $cor)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="cor" value="{{ $cor }}" {{ $categoria->cor === $cor ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-6 h-6 rounded-full peer-checked:ring-2 peer-checked:ring-offset-1 peer-checked:ring-offset-slate-900 peer-checked:ring-white"
                                                style="background-color: {{ $cor }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="editando = false"
                                        class="flex-1 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-xs">Cancelar</button>
                                    <button type="submit"
                                        class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>