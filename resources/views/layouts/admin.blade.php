<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b0d12">
    <title>CSA Admin Panel</title>
    <link rel="icon" href="{{ asset('storage/system/logos/csa-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            if (stored === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
        document.addEventListener('DOMContentLoaded', () => window.lucide?.createIcons());
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased overflow-x-hidden bg-[var(--bg-body)] text-[var(--text-primary)]"
    x-data="{
        isDark: true,
        sidebarExpanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
        animateLayout: false,
        applyTheme() {
            const html = document.documentElement;
            const stored = localStorage.getItem('theme');
            if (stored === 'light') {
                this.isDark = false;
                html.classList.remove('dark');
            } else {
                this.isDark = true;
                html.classList.add('dark');
            }
            const meta = document.querySelector('meta[name=theme-color]');
            if (meta) meta.setAttribute('content', this.isDark ? '#0b0d12' : '#f4f6fb');
        },
        toggleTheme() {
            const next = this.isDark ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            this.applyTheme();
            this.$nextTick(() => window.renderLucide?.());
        },
        init() {
            this.applyTheme();
            window.addEventListener('sidebar-toggled', (event) => {
                this.sidebarExpanded = event.detail.expanded;
                this.animateLayout = true;
            });
        }
    }">

    <x-admin.sidebar />

    {{-- Transisi padding hanya aktif setelah sidebar benar-benar ditoggle,
         sehingga pindah halaman tidak memicu animasi geser. --}}
    <div class="main-content-container flex flex-col min-h-screen"
        :class="[
            animateLayout ? 'transition-[padding] duration-300' : '',
            sidebarExpanded ? 'lg:pl-[240px]' : 'lg:pl-[72px]'
        ]">
        <x-admin.topbar />
        <main class="p-4 sm:p-6 lg:p-8">
            <div class="page-content max-w-[1600px] mx-auto">@yield('content')</div>
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
            modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => { modal.style.opacity = '1'; content.style.transform = 'scale(1)'; });
        }
        function closeDeleteModal(modalId, event) {
            if (event && event.target !== document.getElementById(modalId)) return;
            const modal = document.getElementById(modalId), content = document.getElementById(modalId + 'Content');
            modal.style.opacity = '0'; content.style.transform = 'scale(0.9)';
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }, 300);
        }
        function openImagePreview(modalId, imageUrl, title) {
            const modal = document.getElementById(modalId), content = document.getElementById(modalId + 'Content');
            const img = document.getElementById(modalId + 'Image'), titleEl = document.getElementById(modalId + 'Title'), download = document.getElementById(modalId + 'Download');
            if (img) img.src = imageUrl; if (titleEl) titleEl.textContent = title; if (download) download.href = imageUrl;
            modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => { modal.style.opacity = '1'; content.style.transform = 'scale(1)'; });
        }
        function closeImagePreview(modalId, event) {
            if (event && event.target !== document.getElementById(modalId)) return;
            const modal = document.getElementById(modalId), content = document.getElementById(modalId + 'Content');
            modal.style.opacity = '0'; content.style.transform = 'scale(0.95)';
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }, 300);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            document.querySelectorAll('[id$="Modal"]').forEach((modal) => {
                if (!modal.classList.contains('hidden')) {
                    modal.id.includes('delete') ? closeDeleteModal(modal.id) : closeImagePreview(modal.id);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
