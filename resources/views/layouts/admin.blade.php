<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSA Admin Panel</title>
    <link rel="icon" href="{{ asset('storage/system/logos/csa.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=geist:300,400,500,600,700&family=jetbrains-mono:400,500,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes contentFadeUp {
            0% {
                opacity: 0;
                transform: translateY(32px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-content {
            opacity: 0;
            animation: contentFadeUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        main {
            min-height: calc(100vh - 64px);
        }

        html.sidebar-collapsed .csa-sidebar-root {
            width: 80px !important;
        }

        html.sidebar-collapsed .main-content-container {
            padding-left: 112px !important;
        }

        html.sidebar-collapsed .csa-sidebar-hideable {
            opacity: 0 !important;
            transform: translateX(-16px) !important;
            pointer-events: none !important;
            position: relative !important;
        }

        html.sidebar-collapsed .csa-sidebar-nav-header {
            opacity: 0 !important;
        }

        .csa-sidebar-root {
            width: 280px;
        }

        body.page-ready .csa-sidebar-root {
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.page-ready .main-content-container {
            transition: padding-left 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.page-ready .csa-sidebar-hideable {
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.page-ready .csa-sidebar-nav-header {
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="text-gray-900 font-sans antialiased overflow-x-hidden bg-gray-50"
    x-data="{
        sidebarExpanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true')
    }" @sidebar-toggled.window="sidebarExpanded = $event.detail.expanded">

    <script>
        (function() {
            const isExpanded = JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true');
            if (!isExpanded) {
                document.documentElement.classList.add('sidebar-collapsed');
            }
            document.addEventListener('alpine:initialized', () => {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        document.body.classList.add('page-ready');
                    });
                });
            });
        })();
    </script>

    <x-admin.sidebar />

    <div class="main-content-container flex flex-col min-h-screen"
        :class="sidebarExpanded ? 'pl-[312px]' : 'pl-[112px]'">

        <x-admin.topbar />

        <main class="p-8">
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <x-admin.modal.delete id="deleteModal" />
    <x-admin.modal.image-preview id="imagePreviewModal" />
    <x-admin.modal.docs-preview />

    <x-admin.toast id="globalToast" />

    <script>
        function openDeleteModal(modalId, url) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            const form = document.getElementById(modalId + 'Form');

            if (form) form.action = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modal.style.opacity = '1';
                content.style.transform = 'scale(1)';
            });
        }

        function closeDeleteModal(modalId, event) {
            if (event && event.target !== document.getElementById(modalId)) return;

            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');

            modal.style.opacity = '0';
            content.style.transform = 'scale(0.9)';

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        function openImagePreview(modalId, imageUrl, title) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            const img = document.getElementById(modalId + 'Image');
            const titleEl = document.getElementById(modalId + 'Title');
            const download = document.getElementById(modalId + 'Download');

            if (img) img.src = imageUrl;
            if (titleEl) titleEl.textContent = title;
            if (download) download.href = imageUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modal.style.opacity = '1';
                content.style.transform = 'scale(1)';
            });
        }

        function closeImagePreview(modalId, event) {
            if (event && event.target !== document.getElementById(modalId)) return;

            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');

            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        if (modal.id.includes('delete')) {
                            closeDeleteModal(modal.id);
                        } else {
                            closeImagePreview(modal.id);
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
