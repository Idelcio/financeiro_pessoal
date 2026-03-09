<x-app-layout>
    <x-slot name="title">Entradas</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Entradas</h1>
            <p class="text-slate-400 text-sm mt-1">Dinheiro recebido no mês</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <input type="month" name="mes" value="{{ $mes }}" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
            </form>
            <a href="{{ route('receitas.create') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nova Entrada
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-900/40 border border-emerald-700 text-emerald-300 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <!-- Resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-slate-900 border border-emerald-800/50 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Total do Mês</p>
            <p class="text-2xl font-bold text-emerald-400">R$ {{ number_format($totalMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Fixas / Recorrentes</p>
            <p class="text-2xl font-bold text-white">R$ {{ number_format($recorrentes->sum('valor_centavos') / 100, 2, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">{{ $recorrentes->count() }} entrada(s)</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Extras do Mês</p>
            <p class="text-2xl font-bold text-white">R$ {{ number_format($avulsas->sum('valor_centavos') / 100, 2, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">{{ $avulsas->count() }} entrada(s)</p>
        </div>
    </div>

    <!-- Lista -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl">
        <div class="p-5 border-b border-slate-800">
            <h2 class="font-bold text-white">Entradas de {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('m/Y') }}</h2>
        </div>

        @if($entradas->isEmpty())
            <div class="text-center py-12">
                <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <p class="text-slate-500 text-sm">Nenhuma entrada neste mês.</p>
                <a href="{{ route('receitas.create') }}" class="mt-3 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Registrar entrada</a>
            </div>
        @else
            <div class="divide-y divide-slate-800">
                @foreach($entradas as $receita)
                <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-semibold text-white">{{ $receita->descricao }}</h3>
                            <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">{{ $receita->data->format('d/m/Y') }}</span>
                            @if($receita->tipo === 'empresa')
                                <span class="text-xs px-2 py-0.5 bg-blue-900/50 text-blue-400 rounded-full">Empresa</span>
                            @endif
                            @if($receita->recorrente)
                                <span class="text-xs px-2 py-0.5 bg-emerald-900/40 text-emerald-400 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Recorrente
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">Extra</span>
                            @endif
                        </div>
                        @if($receita->observacao)
                            <p class="text-slate-500 text-xs mt-1">{{ $receita->observacao }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-bold text-emerald-400">
                            + R$ {{ number_format($receita->valor_centavos / 100, 2, ',', '.') }}
                        </span>
                        <a href="{{ route('receitas.edit', $receita) }}" class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('receitas.destroy', $receita) }}"
                            x-data x-on:submit.prevent="if(confirm('Remover esta entrada?')) $el.submit()">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-600 hover:text-rose-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
