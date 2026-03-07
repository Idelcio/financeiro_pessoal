<!DOCTYPE html>
<html lang="pt-BR" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Financeiro - Controle total das suas finanças</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Favicon e PWA -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.svg" />
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#10b981" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Gestor" />

    @env('local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @endenv
</head>

<body class="bg-slate-950 text-slate-100 font-sans antialiased">

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur border-b border-slate-800/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-bold text-white">Gestor Financeiro</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-lg transition-colors">
                        Acessar Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-slate-300 hover:text-white text-sm font-medium transition-colors">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-lg transition-colors">
                        Criar conta grátis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-emerald-950/40 via-slate-950 to-slate-950 pointer-events-none">
        </div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 md:py-32 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-900/50 border border-emerald-700/50 rounded-full text-emerald-300 text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                Controle financeiro e de veículos
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                Suas finanças sob
                <span class="text-emerald-400">controle total</span>
            </h1>
            <p class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Gerencie gastos fixos, cartões de crédito, veículos completos, IPVA, IPTU e muito mais.
                Tudo em um lugar, para sua vida pessoal e para o seu negócio.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-lg transition-colors shadow-lg shadow-emerald-900/30">
                    Começar gratuitamente
                </a>
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-lg transition-colors border border-slate-700">
                    Já tenho conta
                </a>
            </div>
        </div>
    </section>

    <!-- Módulos -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-20">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-white mb-4">Tudo que você precisa em um lugar</h2>
            <p class="text-slate-400 text-lg">Módulos completos para cada área das suas finanças</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-blue-900/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">Gastos Fixos</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Luz, água, telefone, streaming e qualquer conta fixa
                    mensal. Marque como pago e acompanhe o histórico.</p>
            </div>

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-purple-900/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">Cartões de Crédito</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Múltiplos cartões, registro de compras parceladas e
                    controle de parcelas. Saiba exatamente quando e quanto pagar.</p>
            </div>

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-orange-900/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H5a1 1 0 00-1 1v10m0 0H3m10 0h1m0-5l2-3h3l2 3m-8 5h8" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">Gestão de Veículos</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-3">Controle total do seu veículo em um só lugar: abastecimentos, manutenções com alertas, despesas como multas e seguro. Veja o consumo médio e o custo real por km.</p>
                <div class="flex flex-wrap gap-1.5">
                    <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">Abastecimentos</span>
                    <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">Manutenções</span>
                    <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full">Multas e seguro</span>
                    <span class="text-xs px-2 py-0.5 bg-emerald-900/50 text-emerald-400 rounded-full">Consumo km/l</span>
                </div>
            </div>

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-rose-900/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">IPVA e IPTU</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Cadastre seus veículos e imóveis, controle as cotas e
                    nunca mais perca o vencimento de um imposto anual.</p>
            </div>

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-emerald-900/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">Gastos Avulsos</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Lance qualquer gasto com categorias personalizadas.
                    Filtre por mês, categoria e tipo pessoal ou empresa.</p>
            </div>

            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl p-6 hover:border-emerald-700/50 transition-colors">
                <div class="w-10 h-10 bg-slate-700/50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-2">Dashboard Completo</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Visão geral do mês: total gasto, pago, pendente e
                    alertas de vencimentos próximos. Separe pessoal e empresa.</p>
            </div>

        </div>
    </section>

    <!-- CTA Final -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
        <div
            class="bg-gradient-to-br from-emerald-900/40 to-slate-900 border border-emerald-800/30 rounded-3xl p-10 md:p-16 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                Pronto para ter controle das suas finanças?
            </h2>
            <p class="text-slate-400 text-lg mb-8">Crie sua conta e comece a organizar seus gastos agora mesmo.</p>
            <a href="{{ route('register') }}"
                class="inline-block px-10 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-lg transition-colors shadow-lg shadow-emerald-900/30">
                Criar conta gratuitamente
            </a>
        </div>
    </section>

    <footer class="border-t border-slate-800 py-8 text-center text-slate-500 text-sm">
        &copy; {{ date('Y') }} Gestor Financeiro. Todos os direitos reservados.
    </footer>

    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/sw.js").catch(() => { });
            });
        }
    </script>
</body>

</html>