<x-app-layout>
    <x-slot name="title">{{ $imposto->nome }} - {{ strtoupper($imposto->tipo) }}</x-slot>

    <div class="max-w-3xl">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('impostos.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-0.5 {{ $imposto->tipo === 'ipva' ? 'bg-yellow-900/50 text-yellow-400' : 'bg-blue-900/50 text-blue-400' }} rounded-full font-bold uppercase">{{ $imposto->tipo }}</span>
                    <h1 class="text-2xl font-bold text-white">{{ $imposto->nome }}</h1>
                </div>
                @if($imposto->descricao_bem)
                    <p class="text-slate-400 text-sm mt-0.5">{{ $imposto->descricao_bem }}</p>
                @endif
            </div>
            <a href="{{ route('impostos.edit', $imposto) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium rounded-lg transition-colors">Editar</a>
        </div>

        @php
            $pago = $imposto->parcelas->where('pago', true)->sum('valor_centavos');
            $pendente = $imposto->parcelas->where('pago', false)->sum('valor_centavos');
            $progresso = $imposto->valor_total_centavos > 0 ? round(($pago / $imposto->valor_total_centavos) * 100) : 0;
        @endphp

        <!-- Resumo -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
                <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Total</p>
                <p class="text-xl font-bold text-white">R$ {{ number_format($imposto->valor_total_centavos / 100, 2, ',', '.') }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
                <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Pago</p>
                <p class="text-xl font-bold text-emerald-400">R$ {{ number_format($pago / 100, 2, ',', '.') }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
                <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Pendente</p>
                <p class="text-xl font-bold {{ $pendente > 0 ? 'text-rose-400' : 'text-slate-400' }}">R$ {{ number_format($pendente / 100, 2, ',', '.') }}</p>
            </div>
        </div>

        <!-- Progresso -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 mb-6">
            <div class="flex justify-between text-sm text-slate-400 mb-2">
                <span>Progresso de pagamento</span>
                <span>{{ $progresso }}%</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-3">
                <div class="bg-emerald-500 h-3 rounded-full transition-all" style="width: {{ $progresso }}%"></div>
            </div>
        </div>

        <!-- Parcelas -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
            <div class="px-5 py-3">
                <h2 class="text-sm font-semibold text-slate-300">Parcelas</h2>
            </div>
            @foreach($imposto->parcelas->sortBy('numero_parcela') as $parcela)
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-white">Parcela {{ $parcela->numero_parcela }}/{{ $imposto->total_parcelas }}</span>
                            @if($parcela->pago)
                                <span class="text-xs px-2 py-0.5 bg-emerald-900/50 text-emerald-400 rounded-full">Pago</span>
                            @else
                                <span class="text-xs px-2 py-0.5 bg-rose-900/50 text-rose-400 rounded-full">Pendente</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 mt-0.5 text-xs text-slate-400">
                            <span>Vence: {{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</span>
                            @if($parcela->data_pagamento)
                                <span>Pago em: {{ \Carbon\Carbon::parse($parcela->data_pagamento)->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-white">R$ {{ number_format($parcela->valor_centavos / 100, 2, ',', '.') }}</span>
                        @if(!$parcela->pago)
                            <form method="POST" action="{{ route('impostos.parcelas.pagar', $parcela) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg transition-colors">Pagar</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('impostos.parcelas.desfazer', $parcela) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-medium rounded-lg transition-colors">Desfazer</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Remover -->
        <div class="mt-6 flex justify-end">
            <form method="POST" action="{{ route('impostos.destroy', $imposto) }}"
                x-data x-on:submit.prevent="if(confirm('Remover este imposto e todas as parcelas?')) $el.submit()">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-900/30 hover:bg-rose-900/60 text-rose-400 text-sm font-medium rounded-xl transition-colors">Remover Imposto</button>
            </form>
        </div>
    </div>
</x-app-layout>
