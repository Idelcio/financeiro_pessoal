<x-app-layout>
    <x-slot:title>Gastos Avulsos</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Gastos Avulsos</h1>
            <p class="text-slate-400 text-sm mt-1">Despesas não recorrentes</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <form method="GET" class="flex items-center gap-2">
                <input type="month" name="mes" value="{{ $mes }}" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
                <select name="tipo" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
                    <option value="todos" {{ $tipo === 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="pessoal" {{ $tipo === 'pessoal' ? 'selected' : '' }}>Pessoal</option>
                    <option value="empresa" {{ $tipo === 'empresa' ? 'selected' : '' }}>Empresa</option>
                </select>
            </form>
            <a href="{{ route('gastos-avulsos.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Gasto
            </a>
        </div>
    </div>

    <!-- Total -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Total do Mês</p>
            <p class="text-3xl font-bold text-white">R$ {{ number_format($totalMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Lançamentos</p>
            <p class="text-3xl font-bold text-white">{{ $gastos->count() }}</p>
        </div>
    </div>

    @if($gastos->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-slate-500 text-lg">Nenhum gasto neste mês.</p>
            <a href="{{ route('gastos-avulsos.create') }}"
                class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">Registrar
                gasto</a>
        </div>
    @else
        <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
            @foreach($gastos as $gasto)
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-white">{{ $gasto->descricao }}</span>
                            <span
                                class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">{{ \Carbon\Carbon::parse($gasto->data)->format('d/m/Y') }}</span>
                            @if($gasto->tipo === 'empresa')
                                <span class="text-xs px-2 py-0.5 bg-blue-900/50 text-blue-400 rounded-full">Empresa</span>
                            @endif
                            @if($gasto->categoria)
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                    style="background-color: {{ $gasto->categoria->cor }}20; color: {{ $gasto->categoria->cor }}">{{ $gasto->categoria->nome }}</span>
                            @endif
                        </div>
                        @if($gasto->observacao)
                            <p class="text-slate-500 text-xs mt-0.5">{{ $gasto->observacao }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-bold text-white">R$
                            {{ number_format($gasto->valor_centavos / 100, 2, ',', '.') }}</span>
                        <a href="{{ route('gastos-avulsos.edit', $gasto) }}"
                            class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('gastos-avulsos.destroy', $gasto) }}" x-data
                            x-on:submit.prevent="if(confirm('Remover este gasto?')) $el.submit()">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-500 hover:text-rose-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>