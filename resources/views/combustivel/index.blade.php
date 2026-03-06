<x-app-layout>
    <x-slot:title>Combustível</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Combustível</h1>
            <p class="text-slate-400 text-sm mt-1">Histórico de abastecimentos</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <input type="month" name="mes" value="{{ $mes }}" onchange="this.form.submit()"
                    class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
            </form>
            <a href="{{ route('combustivel.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar
            </a>
        </div>
    </div>

    <!-- Total do mês -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Total do Mês</p>
            <p class="text-3xl font-bold text-white">R$ {{ number_format($totalMes / 100, 2, ',', '.') }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Abastecimentos</p>
            <p class="text-3xl font-bold text-white">{{ $abastecimentos->count() }}</p>
        </div>
    </div>

    @if($abastecimentos->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <p class="text-slate-500 text-lg">Nenhum abastecimento neste mês.</p>
            <a href="{{ route('combustivel.create') }}"
                class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">Registrar
                abastecimento</a>
        </div>
    @else
        <div class="bg-slate-900 border border-slate-800 rounded-2xl divide-y divide-slate-800">
            @foreach($abastecimentos as $ab)
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-white">{{ ucfirst($ab->tipo_combustivel) }}</span>
                            <span
                                class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">{{ $ab->data_abastecimento->format('d/m/Y') }}</span>
                            @if($ab->tipo === 'empresa')
                                <span class="text-xs px-2 py-0.5 bg-blue-900/50 text-blue-400 rounded-full">Empresa</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 mt-1 text-sm text-slate-400">
                            @if($ab->litros) <span>{{ number_format($ab->litros, 2, ',', '.') }} L</span> @endif
                            @if($ab->valor_litro_centavos) <span>R$
                            {{ number_format($ab->valor_litro_centavos / 100, 3, ',', '.') }}/L</span> @endif
                            @if($ab->posto) <span>{{ $ab->posto }}</span> @endif
                            @if($ab->km_atual) <span>{{ number_format($ab->km_atual, 0, ',', '.') }} km</span> @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-bold text-white">R$
                            {{ number_format($ab->valor_total_centavos / 100, 2, ',', '.') }}</span>
                        <a href="{{ route('combustivel.edit', $ab) }}"
                            class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('combustivel.destroy', $ab) }}" x-data
                            x-on:submit.prevent="if(confirm('Remover este abastecimento?')) $el.submit()">
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