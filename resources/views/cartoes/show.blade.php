<x-app-layout>
    <x-slot name="title">{{ $cartao->nome }}</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('cartoes.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $cartao->cor }}"></span>
                    <h1 class="text-2xl font-bold text-white">{{ $cartao->nome }}</h1>
                </div>
                @if($cartao->bandeira)<p class="text-slate-400 text-sm">{{ ucfirst($cartao->bandeira) }}</p>@endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <input type="month" name="mes" value="{{ $mes }}" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
            </form>
            <a href="{{ route('cartoes.gastos.create', $cartao) }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nova Compra
            </a>
        </div>
    </div>

    <!-- Resumo do mes -->
    <div x-data="{ pagarFaturaOpen: false }" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Fatura do Mês</p>
            <p class="text-2xl font-bold text-white">R$ {{ number_format($totalFaturaMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-emerald-800/50 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Pago</p>
            <p class="text-2xl font-bold text-emerald-400">R$ {{ number_format($totalPagoMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-rose-800/50 rounded-2xl p-5 flex items-center justify-between gap-3">
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Total Devedor</p>
                <p class="text-2xl font-bold text-rose-400">R$ {{ number_format($totalDevido / 100, 2, ',', '.') }}</p>
            </div>
            @if($totalFaturaMes > $totalPagoMes)
                <button type="button" @click="pagarFaturaOpen = true"
                    class="flex-shrink-0 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors whitespace-nowrap">
                    Pagar Fatura
                </button>
            @endif
        </div>

        {{-- Modal Pagar Fatura --}}
        <div x-show="pagarFaturaOpen" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display:none">
            <div @click.outside="pagarFaturaOpen = false" class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-sm">
                <h3 class="font-bold text-white mb-1">Pagar fatura de {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('m/Y') }}</h3>
                <p class="text-slate-400 text-sm mb-5">Todas as cobranças não pagas deste mês serão marcadas como pagas.</p>
                <form method="POST" action="{{ route('cartoes.pagar-fatura', $cartao) }}">
                    @csrf
                    <input type="hidden" name="mes" value="{{ $mes }}">
                    <div class="mb-4">
                        <label class="block text-sm text-slate-300 mb-1.5">Data do pagamento</label>
                        <input type="date" name="data_pagamento" value="{{ now()->toDateString() }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-3 mb-5 flex items-center justify-between">
                        <span class="text-sm text-slate-400">Total da fatura</span>
                        <span class="font-bold text-white">R$ {{ number_format($totalFaturaMes / 100, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="pagarFaturaOpen = false" class="flex-1 py-2.5 bg-slate-800 text-white rounded-xl text-sm">Cancelar</button>
                        <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Compras e parcelas do mes -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl">
        <div class="p-5 border-b border-slate-800">
            <h2 class="font-bold text-white">Compras com parcela em {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('m/Y') }}</h2>
        </div>

        @if($gastos->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-500">Nenhuma compra registrada.</p>
                <a href="{{ route('cartoes.gastos.create', $cartao) }}" class="mt-3 inline-block text-emerald-400 hover:text-emerald-300 text-sm">Registrar compra</a>
            </div>
        @else
            <div class="divide-y divide-slate-800">
                @foreach($gastos as $gasto)
                    @php $parcelaDoMes = $gasto->parcelas->first(); @endphp
                    @if($parcelaDoMes)
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-semibold text-white">{{ $gasto->descricao }}</h3>
                                    <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">{{ $gasto->data_compra->format('d/m/Y') }}</span>
                                    @if($gasto->tipo === 'empresa')
                                        <span class="text-xs px-2 py-0.5 bg-blue-900/50 text-blue-400 rounded-full">Empresa</span>
                                    @endif
                                    @if($gasto->recorrente)
                                        @if($gasto->recorrente_ativa)
                                            <span class="text-xs px-2 py-0.5 bg-emerald-900/40 text-emerald-400 rounded-full flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Recorrente
                                            </span>
                                        @else
                                            <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-500 rounded-full">Recorrente cancelada</span>
                                        @endif
                                    @endif
                                </div>
                                <p class="text-slate-400 text-sm mt-1">
                                    @if($gasto->recorrente)
                                        Cobrança {{ $parcelaDoMes->numero_parcela }} &bull; R$ {{ number_format($gasto->valor_total_centavos / 100, 2, ',', '.') }}/mês
                                    @else
                                        Parcela {{ $parcelaDoMes->numero_parcela }}/{{ $gasto->total_parcelas }} &bull;
                                        Total: R$ {{ number_format($gasto->valor_total_centavos / 100, 2, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold {{ $parcelaDoMes->pago ? 'text-emerald-400' : 'text-white' }}">
                                    R$ {{ number_format($parcelaDoMes->valor_centavos / 100, 2, ',', '.') }}
                                </span>
                                @if($parcelaDoMes->pago)
                                    <span class="text-xs px-2 py-1 bg-emerald-900/50 text-emerald-400 rounded-lg">Paga {{ $parcelaDoMes->data_pagamento->format('d/m') }}</span>
                                    <form method="POST" action="{{ route('cartoes.parcelas.desfazer', $parcelaDoMes) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-slate-500 hover:text-rose-400 transition-colors">Desfazer</button>
                                    </form>
                                @else
                                    <button type="button"
                                        x-data x-on:click="$dispatch('open-modal', 'pagar-parcela-{{ $parcelaDoMes->id }}')"
                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg transition-colors">
                                        Pagar
                                    </button>
                                @endif
                                @if($gasto->recorrente && $gasto->recorrente_ativa)
                                    <form method="POST" action="{{ route('cartoes.gastos.cancelar-recorrente', $gasto) }}"
                                        x-data x-on:submit.prevent="if(confirm('Cancelar esta recorrência? As próximas cobranças não serão mais geradas.')) $el.submit()">
                                        @csrf
                                        <button type="submit" class="text-xs text-amber-500 hover:text-amber-400 transition-colors whitespace-nowrap">Cancelar recorrência</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('cartoes.gastos.destroy', $gasto) }}"
                                    x-data x-on:submit.prevent="if(confirm('Remover esta compra e todas as parcelas?')) $el.submit()">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-600 hover:text-rose-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if(!$parcelaDoMes->pago)
                        <div x-data="{ open: false }" x-on:open-modal.window="if($event.detail === 'pagar-parcela-{{ $parcelaDoMes->id }}') open = true"
                            x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display:none">
                            <div @click.outside="open = false" class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-sm">
                                <h3 class="font-bold text-white mb-4">Pagar parcela {{ $parcelaDoMes->numero_parcela }} - {{ $gasto->descricao }}</h3>
                                <form method="POST" action="{{ route('cartoes.parcelas.pagar', $parcelaDoMes) }}">
                                    @csrf
                                    <div>
                                        <label class="block text-sm text-slate-300 mb-1.5">Data do pagamento</label>
                                        <input type="date" name="data_pagamento" value="{{ now()->toDateString() }}"
                                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500" required>
                                    </div>
                                    <div class="flex gap-3 mt-5">
                                        <button type="button" @click="open = false" class="flex-1 py-2.5 bg-slate-800 text-white rounded-xl text-sm">Cancelar</button>
                                        <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm">Confirmar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
