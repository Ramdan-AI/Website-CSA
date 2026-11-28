@props([
    'code',
    'title',
    'message',
    'primaryLabel' => 'Kembali ke beranda',
    'primaryHref' => null,
    'secondaryLabel' => null,
    'secondaryHref' => null,
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0a0b" media="(prefers-color-scheme: dark)">
    <title>{{ $code }} · {{ $title }} | CSA</title>
    <link rel="icon" href="{{ asset('images/csa-logo.webp') }}" type="image/webp">
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const dark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    <style>
        @font-face { font-family: 'Inter'; font-weight: 400; font-display: swap; src: url('{{ asset('fonts/inter-400.woff2') }}') format('woff2'); }
        @font-face { font-family: 'Inter'; font-weight: 500; font-display: swap; src: url('{{ asset('fonts/inter-500.woff2') }}') format('woff2'); }
        @font-face { font-family: 'Inter'; font-weight: 600; font-display: swap; src: url('{{ asset('fonts/inter-600.woff2') }}') format('woff2'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: 700; font-display: swap; src: url('{{ asset('fonts/plus-jakarta-sans-700.woff2') }}') format('woff2'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: 800; font-display: swap; src: url('{{ asset('fonts/plus-jakarta-sans-800.woff2') }}') format('woff2'); }
        :root {
            --paper: #ffffff;
            --ink: #0f1419;
            --ink-soft: #4b5563;
            --line-strong: #d1d5db;
            --accent: #1a73e8;
            --accent-soft: #e8f0fe;
            --accent-solid: #1a73e8;
            --accent-solid-hover: #1557b0;
            --on-accent: #ffffff;
            color-scheme: light;
        }
        .dark {
            --paper: #0a0a0b;
            --ink: #f3f4f6;
            --ink-soft: #a1a5ad;
            --line-strong: #34343a;
            --accent: #8ab4f8;
            --accent-soft: #17253d;
            --accent-solid: #8ab4f8;
            --accent-solid-hover: #aecbfa;
            --on-accent: #0a0a0b;
            color-scheme: dark;
        }
        * { box-sizing: border-box; margin: 0; }
        body {
            min-height: 100vh;
            min-height: 100dvh;
            background: var(--paper);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .error-page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1.5rem;
            text-align: center;
        }
        .error-page__brand img { display: block; height: 2.5rem; width: auto; }
        .error-page__code {
            margin-top: 2rem;
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            font-size: clamp(5rem, 24vw, 10rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.05em;
            color: var(--accent);
            animation: error-page-rise .7s cubic-bezier(.19, 1, .22, 1) both;
        }
        .error-page__title {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .error-page__message {
            max-width: 28rem;
            font-size: 1rem;
            line-height: 1.7;
            color: var(--ink-soft);
        }
        .error-page__actions {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 20rem;
            gap: .75rem;
            margin-top: 1rem;
        }
        .error-page__button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 1.5rem;
            border-radius: .5rem;
            font-size: .9375rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color .2s ease;
        }
        .error-page__button:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        .error-page__button--primary { background: var(--accent-solid); color: var(--on-accent); }
        .error-page__button--primary:hover { background: var(--accent-solid-hover); }
        .error-page__button--secondary { color: var(--ink); border: 1px solid var(--line-strong); }
        .error-page__button--secondary:hover { background: var(--accent-soft); }
        @media (min-width: 640px) {
            .error-page__actions { flex-direction: row; justify-content: center; width: auto; max-width: none; }
        }
        @keyframes error-page-rise {
            from { opacity: 0; transform: translateY(1.25rem); }
        }
        @media (prefers-reduced-motion: reduce) {
            .error-page__code { animation: none; }
            .error-page__button { transition: none; }
        }
    </style>
</head>
<body>
    <main class="error-page">
        <a href="{{ url('/') }}" class="error-page__brand" aria-label="Beranda CSA">
            <img src="{{ asset('images/csa-logo.webp') }}" alt="CSA">
        </a>
        <p class="error-page__code" aria-hidden="true">{{ $code }}</p>
        <h1 class="error-page__title">{{ $title }}</h1>
        <p class="error-page__message">{{ $message }}</p>
        <div class="error-page__actions">
            <a href="{{ $primaryHref ?? url('/') }}" class="error-page__button error-page__button--primary">{{ $primaryLabel }}</a>
            @if ($secondaryLabel)
                <a href="{{ $secondaryHref ?? url('/') }}" class="error-page__button error-page__button--secondary">{{ $secondaryLabel }}</a>
            @endif
        </div>
    </main>
</body>
</html>
