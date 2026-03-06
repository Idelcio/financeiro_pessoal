<x-app-layout>
    <x-slot:title>Gastos Fixos</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Gastos Fixos</h1>
            <p class="text-slate-400 text-sm mt-1">Contas mensais fixas</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <input type="month" name="mes" value="{{ $mes }}" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
            </form>
            <a href="{{ route('gastos-fixos.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Gasto
            </a>
        </div>
    </div>

    @if($gastos->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-slate-500 text-lg">Nenhum gasto fixo cadastrado.</p>
            <a href="{{ route('gastos-fixos.create') }}"
                class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">Cadastrar
                agora</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($gastos as $gasto)
                @php $pago = isset($pagamentosDoMes[$gasto->id]); @endphp
                <div
                    class="bg-slate-900 border {{ $pago ? 'border-emerald-800/50' : 'border-slate-800' }} rounded-2xl p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-white">{{ $gasto->nome }}</h3>
                                <span
                                    class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">{{ $gasto->tipo_gasto }}</span>
                                <span
                                    class="text-xs px-2 py-0.5 {{ $gasto->tipo === 'empresa' ? 'bg-blue-900/50 text-blue-400' : 'bg-slate-800 text-slate-400' }} rounded-full">{{ ucfirst($gasto->tipo) }}</span>
                            </div>
                            <p class="text-slate-400 text-sm mt-1">Vence dia {{ $gasto->dia_vencimento }} &bull; R$
                                {{ number_format($gasto->valor_centavos / 100, 2, ',', '.') }}/mês</p>
                        </div>

                        <div class="flex items-center gap-3 flex-wrap">
                            @if($pago)
                                <span class="flex items-center gap-1.5 text-sm text-emerald-400 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pago em {{ $pagamentosDoMes[$gasto->id]->data_pagamento->format('d/m') }}
                                </span>
                                <form method="POST"
                                    action="{{ route('gastos-fixos.pagamento.destroy', $pagamentosDoMes[$gasto->id]) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-slate-500 hover:text-rose-400 transition-colors">Desfazer</button>
                                </form>
                            @else
                                <span class="text-sm text-rose-400 font-medium">Pendente</span>
                                <button type="button" x-data x-on:click="$dispatch('open-modal', 'pagar-{{ $gasto->id }}')"
                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg transition-colors">
                                    Marcar como pago
                                </button>
                            @endif

                            <a href="{{ route('gastos-fixos.edit', $gasto) }}"
                                class="text-slate-500 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('gastos-fixos.destroy', $gasto) }}" x-data
                                x-on:submit.prevent="if(confirm('Remover este gasto fixo?')) $el.submit()">
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
                </div>

                <!-- Modal de pagamento -->
                @if(!$pago)
                    <div x-data="{ open: false }"
                        x-on:open-modal.window="if($event.detail === 'pagar-{{ $gasto->id }}') open = true" x-show="open"
                        x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
                        style="display:none">
                        <div @click.outside="open = false"
                            class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md">
                            <h3 class="font-bold text-white mb-4">Registrar pagamento - {{ $gasto->nome }}</h3>
                            <form method="POST" action="{{ route('gastos-fixos.pagar', $gasto) }}">
                                @csrf
                                <input type="hidden" name="mes_referencia" value="{{ $mes }}">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-slate-300 mb-1.5">Valor pago (R$)</label>
                                        <input type="number" step="0.01" name="valor_pago"
                                            value="{{ number_format($gasto->valor_centavos / 100, 2, '.', '') }}"
                                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-300 mb-1.5">Data do pagamento</label>
                                        <input type="date" name="data_pagamento" value="{{ now()->toDateString() }}"
                                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500"
                                            required>
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-6">
                                    <button type="button" @click="open = false"
                                        class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors text-sm">Cancelar</button>
                                    <button type="submit"
                                        class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</x-app-layout>