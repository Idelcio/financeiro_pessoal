<x-app-layout>
    <x-slot:title>Meus Veículos</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Meus Veículos</h1>
            <p class="text-slate-400 text-sm mt-1">Gestão completa de combustível, manutenção e despesas</p>
        </div>
        <a href="{{ route('veiculos.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Veículo
        </a>
    </div>

    @if($veiculos->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H5a1 1 0 00-1 1v10m0 0H3m10 0h1m0-5l2-3h3l2 3m-8 5h8" />
            </svg>
            <p class="text-slate-500 text-lg">Nenhum veículo cadastrado.</p>
            <a href="{{ route('veiculos.create') }}"
                class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">
                Cadastrar veículo
            </a>
        </div>
    @else
        <p class="text-slate-500 text-sm mb-4">Clique no veículo para registrar abastecimentos, manutenções e despesas.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($veiculos as $veiculo)
                @php
                    $custoTotal = $veiculo->custo_total_centavos;
                    $consumo = $veiculo->consum_medio;
                @endphp
                <a href="{{ route('veiculos.show', $veiculo) }}"
                    class="block bg-slate-900 border border-slate-800 hover:border-slate-600 rounded-2xl p-5 transition-colors group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="font-bold text-white text-lg group-hover:text-emerald-400 transition-colors">
                                {{ $veiculo->nome }}
                            </h2>
                            @if($veiculo->marca || $veiculo->modelo)
                                <p class="text-slate-400 text-sm">{{ implode(' ', array_filter([$veiculo->marca, $veiculo->modelo, $veiculo->ano])) }}</p>
                            @endif
                        </div>
                        @if($veiculo->placa)
                            <span class="text-xs font-mono bg-slate-800 text-slate-300 px-2 py-1 rounded-lg">
                                {{ strtoupper($veiculo->placa) }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-800/60 rounded-xl p-3">
                            <p class="text-slate-500 text-xs mb-1">KM Atual</p>
                            <p class="text-white font-semibold text-sm">
                                {{ $veiculo->km_atual ? number_format($veiculo->km_atual, 0, ',', '.') . ' km' : '—' }}
                            </p>
                        </div>
                        <div class="bg-slate-800/60 rounded-xl p-3">
                            <p class="text-slate-500 text-xs mb-1">Consumo Médio</p>
                            <p class="text-white font-semibold text-sm">
                                {{ $consumo ? $consumo . ' km/l' : '—' }}
                            </p>
                        </div>
                        <div class="bg-slate-800/60 rounded-xl p-3">
                            <p class="text-slate-500 text-xs mb-1">Manutenções</p>
                            <p class="text-white font-semibold text-sm">{{ $veiculo->manutencoes->count() }}</p>
                        </div>
                        <div class="bg-slate-800/60 rounded-xl p-3">
                            <p class="text-slate-500 text-xs mb-1">Custo Total</p>
                            <p class="text-white font-semibold text-sm">R$ {{ number_format($custoTotal / 100, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-1 bg-slate-800 text-slate-400 rounded-full capitalize">
                                {{ str_replace('_', ' ', $veiculo->tipo_combustivel) }}
                            </span>
                            @if(!$veiculo->ativo)
                                <span class="text-xs px-2 py-1 bg-rose-900/40 text-rose-400 rounded-full">Inativo</span>
                            @endif
                        </div>
                        <span class="flex items-center gap-1 text-xs text-emerald-400 group-hover:text-emerald-300 font-medium transition-colors">
                            Ver detalhes
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
