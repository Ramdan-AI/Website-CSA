<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSA Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>

<body>
    @include('layouts.partials.sidebar')

    <div class="main-wrapper">
        @include('layouts.partials.topbar')

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <x-toast />
    <x-modal-delete />

    @stack('scripts')
</body>
</html>