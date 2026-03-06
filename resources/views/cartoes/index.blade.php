<x-app-layout>
    <x-slot:title>Cartões de Crédito</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Cartões de Crédito</h1>
            <p class="text-slate-400 text-sm mt-1">Gerencie seus cartões e parcelas</p>
        </div>
        <a href="{{ route('cartoes.create') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Cartão
        </a>
    </div>

    @if($cartoes->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <p class="text-slate-500 text-lg">Nenhum cartão cadastrado.</p>
            <a href="{{ route('cartoes.create') }}" class="mt-4 inline-block px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl text-sm transition-colors">Adicionar cartão</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($cartoes as $cartao)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                    <!-- Card Header com cor -->
                    <div class="h-2" style="background-color: {{ $cartao->cor }}"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-white text-lg">{{ $cartao->nome }}</h3>
                                @if($cartao->bandeira)
                                    <p class="text-slate-400 text-sm">{{ ucfirst($cartao->bandeira) }}</p>
                                @endif
                            </div>
                            <span class="text-xs px-2 py-0.5 {{ $cartao->tipo === 'empresa' ? 'bg-blue-900/50 text-blue-400' : 'bg-slate-800 text-slate-400' }} rounded-full">{{ ucfirst($cartao->tipo) }}</span>
                        </div>

                        @if($cartao->limite_centavos)
                            <p class="text-xs text-slate-500 mb-1">Limite</p>
                            <p class="text-slate-300 text-sm font-medium mb-3">R$ {{ number_format($cartao->limite_centavos / 100, 2, ',', '.') }}</p>
                        @endif

                        @if($cartao->dia_vencimento)
                            <p class="text-xs text-slate-500">Vence dia {{ $cartao->dia_vencimento }}</p>
                        @endif

                        <p class="text-xs text-slate-500 mt-1">{{ $cartao->gastos_count }} compras registradas</p>

                        <div class="flex items-center gap-2 mt-5">
                            <a href="{{ route('cartoes.show', $cartao) }}" class="flex-1 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors text-center">
                                Ver detalhes
                            </a>
                            <a href="{{ route('cartoes.edit', $cartao) }}" class="p-2 text-slate-500 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
