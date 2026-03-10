<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-slate-400 text-sm mt-1">Visão geral das suas finanças</p>
        </div>
        <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
            <input type="month" name="mes" value="{{ $mes }}"
                class="w-full sm:w-auto bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
            <select name="tipo" class="w-full sm:w-auto bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
                <option value="todos" {{ $tipo === 'todos' ? 'selected' : '' }}>Todos</option>
                <option value="pessoal" {{ $tipo === 'pessoal' ? 'selected' : '' }}>Pessoal</option>
                <option value="empresa" {{ $tipo === 'empresa' ? 'selected' : '' }}>Empresa</option>
            </select>
            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition-colors">Filtrar</button>
        </form>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-900 border border-emerald-800/50 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Entradas</p>
            <p class="text-3xl font-bold text-emerald-400">R$ {{ number_format($totalReceitas / 100, 2, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">
                <a href="{{ route('receitas.index', ['mes' => $mes]) }}" class="hover:text-emerald-400 transition-colors">Ver entradas</a>
            </p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Gastos do Mês</p>
            <p class="text-3xl font-bold text-white">R$ {{ number_format($totalGeralMes / 100, 2, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">Fixos + Cartões + Veículos + Avulsos + Impostos</p>
        </div>
        <div class="bg-slate-900 border {{ $saldo >= 0 ? 'border-emerald-800/50' : 'border-rose-800/50' }} rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Saldo</p>
            <p class="text-3xl font-bold {{ $saldo >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                {{ $saldo >= 0 ? '' : '-' }}R$ {{ number_format(abs($saldo) / 100, 2, ',', '.') }}
            </p>
            <p class="text-slate-500 text-xs mt-1">Entradas - Gastos</p>
        </div>
        <div class="bg-slate-900 border border-rose-800/50 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Pendente</p>
            <p class="text-3xl font-bold text-rose-400">R$ {{ number_format($totalGeralPendente / 100, 2, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">A pagar este mês</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Alertas de vencimento -->
        @if($fixosVencendo->count() > 0 || $impostosVencendo->count() > 0)
        <div class="bg-slate-900 border border-amber-800/50 rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Vencendo em 7 dias
            </h2>
            <div class="space-y-2">
                @foreach($fixosVencendo as $gasto)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div class="pr-3 flex-1">
                            <p class="text-sm font-medium text-white line-clamp-1">{{ $gasto->nome }}</p>
                            <p class="text-xs text-slate-400">Vence dia {{ $gasto->dia_vencimento }}</p>
                        </div>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <span class="text-sm font-semibold text-amber-400">R$ {{ number_format($gasto->valor_centavos / 100, 2, ',', '.') }}</span>
                            <a href="{{ route('gastos-fixos.edit', $gasto) }}" class="p-1 text-slate-600 hover:text-slate-400 transition-colors" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
                @foreach($impostosVencendo as $parcela)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div class="pr-3 flex-1">
                            <p class="text-sm font-medium text-white line-clamp-1">
                                {{ $parcela->imposto?->nome ?? 'Imposto removido' }}
                                <span class="text-slate-400">({{ $parcela->numero_parcela }}x)</span>
                            </p>
                            <p class="text-xs text-slate-400">Vence em {{ $parcela->data_vencimento->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <span class="text-sm font-semibold text-amber-400">R$ {{ number_format($parcela->valor_centavos / 100, 2, ',', '.') }}</span>
                            <form method="POST" action="{{ route('impostos.parcelas.destroy', $parcela) }}"
                                x-data x-on:submit.prevent="if(confirm('Remover esta parcela?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-slate-600 hover:text-rose-400 transition-colors" title="Remover parcela">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Gastos fixos do mes -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">Gastos Fixos</h2>
                <a href="{{ route('gastos-fixos.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300">Ver todos</a>
            </div>
            @if($gastosFixos->isEmpty())
                <p class="text-slate-500 text-sm py-4 text-center">Nenhum gasto fixo cadastrado.</p>
            @else
                <div class="space-y-2">
                    @foreach($gastosFixos->take(6) as $gasto)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-4 py-3 border-b border-slate-800/50 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3 pr-2">
                                @if(in_array($gasto->id, $pagamentosDoMes))
                                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full flex-shrink-0"></span>
                                @else
                                    <span class="w-2.5 h-2.5 bg-slate-600 rounded-full flex-shrink-0"></span>
                                @endif
                                <p class="text-sm font-medium text-white line-clamp-1">{{ $gasto->nome }}</p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 pl-8 sm:pl-0 mt-1 sm:mt-0">
                                <span class="text-sm font-medium {{ in_array($gasto->id, $pagamentosDoMes) ? 'text-emerald-400' : 'text-slate-300' }}">
                                    R$ {{ number_format($gasto->valor_centavos / 100, 2, ',', '.') }}
                                </span>
                                @if(!in_array($gasto->id, $pagamentosDoMes))
                                    <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-rose-900/40 text-rose-400 rounded-lg">Pendente</span>
                                @else
                                    <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-emerald-900/40 text-emerald-400 rounded-lg">Pago</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Gastos com Veiculos do mes -->
        @if($totalVeiculosMes > 0)
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">Veículos este mês</h2>
                <a href="{{ route('veiculos.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300">Ver veículos</a>
            </div>
            <div class="space-y-1">
                @foreach($combustiveisMes->take(3) as $item)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-white">⛽ Combustível{{ $item->veiculo ? ' — '.$item->veiculo->nome : '' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->data_abastecimento->format('d/m') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-300">R$ {{ number_format($item->valor_total_centavos / 100, 2, ',', '.') }}</span>
                    </div>
                @endforeach
                @foreach($manutencoesMes->take(3) as $item)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-white">🔧 {{ $item->tipo_label }}{{ $item->veiculo ? ' — '.$item->veiculo->nome : '' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->data->format('d/m') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-300">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
                    </div>
                @endforeach
                @foreach($despesasVeiculoMes->take(3) as $item)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-white">🚗 {{ $item->tipo_label }}{{ $item->veiculo ? ' — '.$item->veiculo->nome : '' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->data->format('d/m') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-300">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-slate-800 flex justify-between text-sm">
                <span class="text-slate-400">Total veículos</span>
                <span class="font-bold text-white">R$ {{ number_format($totalVeiculosMes / 100, 2, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- Gastos Avulsos do mes -->
        @if($totalAvulsosMes > 0)
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">Gastos Avulsos</h2>
                <a href="{{ route('gastos-avulsos.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300">Ver todos</a>
            </div>
            <div class="space-y-1">
                @foreach($gastosAvulsosMes->take(6) as $item)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-white line-clamp-1">{{ $item->descricao }}</p>
                            <p class="text-xs text-slate-400">{{ $item->data->format('d/m') }}{{ $item->categoria ? ' · '.$item->categoria->nome : '' }}</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-300">R$ {{ number_format($item->valor_centavos / 100, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-slate-800 flex justify-between text-sm">
                <span class="text-slate-400">Total avulsos</span>
                <span class="font-bold text-white">R$ {{ number_format($totalAvulsosMes / 100, 2, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- Impostos do mes -->
        @if($totalImpostosMes > 0)
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">Impostos</h2>
                <a href="{{ route('impostos.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300">Ver todos</a>
            </div>
            <div class="space-y-1">
                @foreach($impostosDoMes as $parcela)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-800/50 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-white line-clamp-1">
                                {{ $parcela->imposto?->nome ?? 'Imposto removido' }}
                                <span class="text-slate-500 text-xs">({{ $parcela->numero_parcela }}x)</span>
                            </p>
                            <p class="text-xs text-slate-400">Vence {{ $parcela->data_vencimento->format('d/m') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-slate-300">R$ {{ number_format($parcela->valor_centavos / 100, 2, ',', '.') }}</span>
                            @if($parcela->pago)
                                <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-emerald-900/40 text-emerald-400 rounded-lg">Pago</span>
                            @else
                                <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-rose-900/40 text-rose-400 rounded-lg">Pend.</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-slate-800 flex justify-between text-sm">
                <span class="text-slate-400">Total impostos</span>
                <span class="font-bold text-white">R$ {{ number_format($totalImpostosMes / 100, 2, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- Parcelas de cartao do mes -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-4">
                <h2 class="font-bold text-white">Parcelas do Mês</h2>
                <a href="{{ route('cartoes.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors">Ver cartões</a>
            </div>
            @if($parcelasMes->isEmpty())
                <p class="text-slate-500 text-sm py-4 text-center">Nenhuma parcela para este mês.</p>
            @else
                <div class="space-y-1">
                    @foreach($parcelasMes->take(6) as $parcela)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-4 py-3 border-b border-slate-800/50 last:border-0 last:pb-0">
                            <div class="pr-2">
                                <p class="text-sm font-medium text-white line-clamp-1">{{ $parcela->cartaoGasto?->descricao ?? '—' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $parcela->cartaoGasto?->cartao?->nome ?? '—' }} - Parcela {{ $parcela->numero_parcela }}/{{ $parcela->cartaoGasto?->total_parcelas ?? '?' }}</p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 mt-1 sm:mt-0">
                                <span class="text-sm font-medium text-slate-300">R$ {{ number_format($parcela->valor_centavos / 100, 2, ',', '.') }}</span>
                                @if($parcela->pago)
                                    <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-emerald-900/40 text-emerald-400 rounded-lg">Paga</span>
                                @else
                                    <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-1 bg-rose-900/40 text-rose-400 rounded-lg">Pend.</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
