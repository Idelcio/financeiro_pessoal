<x-app-layout>
    <x-slot:title>{{ $veiculo->nome }}</x-slot:title>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('veiculos.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-1 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Meus Veículos
            </a>
            <h1 class="text-2xl font-bold text-white">{{ $veiculo->nome }}</h1>
            @if($veiculo->marca || $veiculo->modelo)
                <p class="text-slate-400 text-sm mt-0.5">
                    {{ implode(' ', array_filter([$veiculo->marca, $veiculo->modelo, $veiculo->ano])) }}
                    @if($veiculo->placa) &mdash; <span class="font-mono">{{ strtoupper($veiculo->placa) }}</span> @endif
                </p>
            @endif
        </div>
        <a href="{{ route('veiculos.edit', $veiculo) }}"
            class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </a>
    </div>

    {{-- Alertas de manutenção --}}
    @if($alertas->isNotEmpty())
        <div class="mb-6 space-y-2">
            @foreach($alertas as $alerta)
                <div class="flex items-center gap-3 bg-amber-900/20 border border-amber-700/40 rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-amber-300 text-sm font-medium">Manutenção próxima: {{ $alerta->tipo_label }}</p>
                        <p class="text-amber-400/70 text-xs">{{ $alerta->descricao }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Cards de resumo --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <p class="text-slate-500 text-xs uppercase tracking-wide mb-1">KM Atual</p>
            <p class="text-xl font-bold text-white">
                {{ $veiculo->km_atual ? number_format($veiculo->km_atual, 0, ',', '.') : '—' }}
                @if($veiculo->km_atual) <span class="text-sm font-normal text-slate-500">km</span> @endif
            </p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <p class="text-slate-500 text-xs uppercase tracking-wide mb-1">Consumo Médio</p>
            <p class="text-xl font-bold text-white">
                {{ $veiculo->consum_medio ?? '—' }}
                @if($veiculo->consum_medio) <span class="text-sm font-normal text-slate-500">km/l</span> @endif
            </p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <p class="text-slate-500 text-xs uppercase tracking-wide mb-1">Combustível (mês)</p>
            <p class="text-xl font-bold text-white">R$ {{ number_format($combustivelMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <p class="text-slate-500 text-xs uppercase tracking-wide mb-1">Outras Despesas (mês)</p>
            <p class="text-xl font-bold text-white">R$ {{ number_format($despesasMes / 100, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Coluna esquerda: Combustível --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Abastecimentos</h2>
                <a href="{{ route('combustivel.create') }}"
                    class="flex items-center gap-1.5 text-sm text-emerald-400 hover:text-emerald-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrar
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
                @forelse($abastecimentos as $ab)
                    <div class="p-4 flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-white text-sm capitalize">{{ $ab->tipo_combustivel }}</span>
                                <span class="text-xs text-slate-500">{{ $ab->data_abastecimento->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-0.5 text-xs text-slate-500 flex-wrap">
                                @if($ab->litros) <span>{{ number_format($ab->litros, 2, ',', '.') }} L</span> @endif
                                @if($ab->km_atual) <span>{{ number_format($ab->km_atual, 0, ',', '.') }} km</span> @endif
                                @if($ab->posto) <span>{{ $ab->posto }}</span> @endif
                            </div>
                        </div>
                        <span class="font-bold text-white text-sm whitespace-nowrap">
                            R$ {{ number_format($ab->valor_total_centavos / 100, 2, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500 text-sm">Nenhum abastecimento registrado.</div>
                @endforelse
            </div>
        </div>

        {{-- Coluna direita: Manutenções --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Manutenções</h2>
                <a href="{{ route('veiculos.manutencoes.create', $veiculo) }}"
                    class="flex items-center gap-1.5 text-sm text-emerald-400 hover:text-emerald-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrar
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
                @forelse($manutencoes as $man)
                    <div class="p-4 flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-white text-sm">{{ $man->tipo_label }}</span>
                                <span class="text-xs text-slate-500">{{ $man->data->format('d/m/Y') }}</span>
                                @if($man->alerta_km || $man->alerta_data)
                                    <span class="text-xs px-1.5 py-0.5 bg-amber-900/40 text-amber-400 rounded-full">Próxima!</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 mt-0.5 text-xs text-slate-500 flex-wrap">
                                <span>{{ $man->descricao }}</span>
                                @if($man->km_na_manutencao) <span>{{ number_format($man->km_na_manutencao, 0, ',', '.') }} km</span> @endif
                                @if($man->oficina) <span>{{ $man->oficina }}</span> @endif
                            </div>
                            @if($man->proxima_data || $man->proxima_km)
                                <p class="text-xs text-slate-600 mt-1">
                                    Próxima:
                                    @if($man->proxima_data) {{ $man->proxima_data->format('d/m/Y') }} @endif
                                    @if($man->proxima_km) / {{ number_format($man->proxima_km, 0, ',', '.') }} km @endif
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($man->valor_centavos)
                                <span class="font-bold text-white text-sm whitespace-nowrap">
                                    R$ {{ number_format($man->valor_centavos / 100, 2, ',', '.') }}
                                </span>
                            @endif
                            <a href="{{ route('veiculos.manutencoes.edit', [$veiculo, $man]) }}"
                                class="text-slate-500 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('veiculos.manutencoes.destroy', [$veiculo, $man]) }}"
                                x-data x-on:submit.prevent="if(confirm('Remover esta manutenção?')) $el.submit()">
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
                @empty
                    <div class="p-6 text-center text-slate-500 text-sm">Nenhuma manutenção registrada.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Despesas avulsas do veículo --}}
    <div class="mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-white">Outras Despesas</h2>
            <a href="{{ route('veiculos.despesas.create', $veiculo) }}"
                class="flex items-center gap-1.5 text-sm text-emerald-400 hover:text-emerald-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
            @forelse($veiculo->despesas()->orderByDesc('data')->take(10)->get() as $desp)
                <div class="p-4 flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-medium text-white text-sm">{{ $desp->tipo_label }}</span>
                            <span class="text-xs text-slate-500">{{ $desp->data->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $desp->descricao }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-white text-sm whitespace-nowrap">
                            R$ {{ number_format($desp->valor_centavos / 100, 2, ',', '.') }}
                        </span>
                        <a href="{{ route('veiculos.despesas.edit', [$veiculo, $desp]) }}"
                            class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('veiculos.despesas.destroy', [$veiculo, $desp]) }}"
                            x-data x-on:submit.prevent="if(confirm('Remover esta despesa?')) $el.submit()">
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
            @empty
                <div class="p-6 text-center text-slate-500 text-sm">Nenhuma despesa registrada.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
