<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ $title ?? '' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- PWA / iOS -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Gestor">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
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

<body class="h-full bg-slate-950 text-slate-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

        <!-- Logo -->
        <a href="{{ route('welcome') }}" class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg leading-none">Gestor Financeiro</p>
                <p class="text-emerald-400 text-xs font-medium mt-0.5">Controle total das suas finanças</p>
            </div>
        </a>

        <!-- Card -->
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8">
            {{ $slot }}
        </div>

    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => { });
            });
        }
    </script>
</body>

</html>