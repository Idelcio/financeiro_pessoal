<x-app-layout>
    <x-slot:title>IPVA / IPTU</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">IPVA / IPTU</h1>
            <p class="text-slate-400 text-sm mt-1">Impostos anuais de veículos e imóveis</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <select name="ano" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
                    @for($y = now()->year + 1; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $ano == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('impostos.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Imposto
            </a>
        </div>
    </div>

    @if($impostos->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500 text-lg">Nenhum imposto cadastrado para {{ $ano }}.</p>
            <a href="{{ route('impostos.create') }}"
                class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">Cadastrar
                IPVA/IPTU</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($impostos as $imposto)
                @php
                    $pago = $imposto->parcelas->where('pago', true)->sum('valor_centavos');
                    $pendente = $imposto->parcelas->where('pago', false)->sum('valor_centavos');
                    $progresso = $imposto->valor_total_centavos > 0 ? round(($pago / $imposto->valor_total_centavos) * 100) : 0;
                @endphp
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span
                                    class="text-xs px-2 py-0.5 {{ $imposto->tipo === 'ipva' ? 'bg-yellow-900/50 text-yellow-400' : 'bg-blue-900/50 text-blue-400' }} rounded-full font-bold uppercase">{{ $imposto->tipo }}</span>
                                <h3 class="font-bold text-white">{{ $imposto->nome }}</h3>
                                @if($imposto->tipo_uso === 'empresa')
                                    <span class="text-xs px-2 py-0.5 bg-blue-900/30 text-blue-400 rounded-full">Empresa</span>
                                @endif
                            </div>
                            @if($imposto->descricao_bem)
                                <p class="text-slate-400 text-sm mb-3">{{ $imposto->descricao_bem }}</p>
                            @endif
                            <!-- Barra de progresso -->
                            <div class="w-full bg-slate-800 rounded-full h-2 mb-2">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $progresso }}%">
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Pago: R$ {{ number_format($pago / 100, 2, ',', '.') }}</span>
                                <span>{{ $progresso }}%</span>
                                <span>Total: R$ {{ number_format($imposto->valor_total_centavos / 100, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($pendente > 0)
                                <span class="text-sm text-rose-400 font-medium">R$ {{ number_format($pendente / 100, 2, ',', '.') }}
                                    pendente</span>
                            @else
                                <span class="text-sm text-emerald-400 font-medium">Quitado</span>
                            @endif
                            <a href="{{ route('impostos.show', $imposto) }}"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium rounded-lg transition-colors">Detalhes</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>