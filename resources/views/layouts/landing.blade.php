<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Student Association — Where Creators Meet Builders</title>
    <meta name="description" content="Organisasi berbasis IT di STMIK Mardira Indonesia. Pemrograman, Multimedia, dan segala hal tentang teknologi kreatif.">
    <link rel="icon" href="{{ asset('storage/system/logos/csa-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&family=jetbrains-mono:400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .selection\:bg-blue-100 ::selection {
            background: rgba(59, 130, 246, 0.15);
        }
        html {
            scroll-behavior: smooth;
        }
        .glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 0.5px solid rgba(255, 255, 255, 0.8);
        }
        .glow-blue {
            box-shadow: 0 0 60px -10px rgba(59, 130, 246, 0.15), 0 0 120px -20px rgba(59, 130, 246, 0.08);
        }
        .glow-emerald {
            box-shadow: 0 0 60px -10px rgba(16, 185, 129, 0.15), 0 0 120px -20px rgba(16, 185, 129, 0.08);
        }
    </style>
</head>
<body class="antialiased">
    <x-public.navigation.navbar />

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>