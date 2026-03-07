<x-app-layout>
    <x-slot:title>Painel Admin</x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Painel Admin</h1>
            <p class="text-slate-400 text-sm mt-1">Gerencie os usuários do sistema</p>
        </div>
    </div>

    {{-- Cards de resumo --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Total de Usuários</p>
            <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
        </div>
        <div class="bg-slate-900 border border-emerald-800/50 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Administradores</p>
            <p class="text-3xl font-bold text-emerald-400">{{ $admins }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Usuários Comuns</p>
            <p class="text-3xl font-bold text-slate-300">{{ $regulares }}</p>
        </div>
    </div>

    {{-- Tabela de usuários --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800">
            <h2 class="font-bold text-white">Todos os Usuários</h2>
        </div>

        @if($users->isEmpty())
            <div class="text-center py-16">
                <p class="text-slate-500">Nenhum usuário cadastrado.</p>
            </div>
        @else
            <div class="divide-y divide-slate-800">
                @foreach($users as $user)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Avatar --}}
                            <div
                                class="w-10 h-10 {{ $user->isAdmin() ? 'bg-emerald-600' : 'bg-slate-700' }} rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-white text-sm truncate">{{ $user->name }}</p>
                                    @if($user->isAdmin())
                                        <span
                                            class="text-[10px] uppercase tracking-wider font-semibold px-2 py-0.5 bg-emerald-900/40 text-emerald-400 rounded-lg">Admin</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Cadastrado em {{ $user->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pl-13 sm:pl-0 flex-shrink-0">
                            {{-- Toggle admin --}}
                            @if($user->email !== 'idelcioforest@gmail.com')
                                <form method="POST" action="{{ route('admin.toggle-admin', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $user->isAdmin() ? 'bg-amber-900/40 text-amber-400 hover:bg-amber-900/60' : 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' }}">
                                        {{ $user->isAdmin() ? 'Remover Admin' : 'Tornar Admin' }}
                                    </button>
                                </form>

                                {{-- Excluir --}}
                                <form method="POST" action="{{ route('admin.destroy', $user) }}" x-data
                                    x-on:submit.prevent="if(confirm('Excluir {{ $user->name }}? Esta ação não pode ser desfeita.')) $el.submit()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium bg-slate-800 text-slate-500 hover:bg-rose-900/40 hover:text-rose-400 rounded-lg transition-colors">
                                        Excluir
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-600 italic">Admin principal</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>