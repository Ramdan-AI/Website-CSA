<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSA Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        /* =========================================================
           1. CSS VARIABLES & RESET
           ========================================================= */
        :root {
            --csa-blue: #3664AF;
            --csa-green: #45B84B;
            --bg-body: #F4F7FB;
            --surface: #FFFFFF;
            --border-soft: #E2E8F0;
            --border-hard: #CBD5E1;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --sidebar-hover: #F8FAFC;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        /* =========================================================
           2. LAYOUT: SIDEBAR & TOPBAR
           ========================================================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--surface);
            border-right: 1px solid var(--border-soft);
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-soft);
        }

        .sidebar-brand i {
            color: var(--csa-blue);
            font-size: 1.4rem;
        }

        .sidebar-brand span.dot {
            color: var(--csa-green);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background-color: var(--border-hard);
            border-radius: 4px;
        }

        .menu-header {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 12px 8px;
            margin-top: 8px;
        }

        .sidebar-link {
            height: 42px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            padding: 0 12px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: var(--text-main);
        }

        .sidebar-link.active {
            background: #F0F4FA;
            color: var(--csa-blue);
            font-weight: 600;
            border-left: 3px solid var(--csa-green);
        }

        .sidebar-link.active i {
            color: var(--csa-blue);
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 70px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .profile-box {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-info {
            text-align: right;
            line-height: 1.2;
        }

        .profile-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .profile-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: capitalize;
            font-weight: 500;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--csa-blue);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .logout-btn {
            border: 1px solid var(--border-soft);
            background: transparent;
            color: var(--text-muted);
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #FEE2E2;
            color: #DC2626;
            border-color: #FECACA;
        }

        .main-content {
            padding: 32px;
            flex: 1;
        }

        /* =========================================================
           3. GLOBAL COMPONENTS (Buttons, Tables, Forms)
           ========================================================= */
        /* Headers */
        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
        }

        /* Buttons */
        .btn-csa-primary {
            background-color: var(--csa-blue);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 24px;
            border-radius: 10px;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(54, 100, 175, 0.1);
            cursor: pointer;
        }

        .btn-csa-primary:hover {
            background-color: #2a508f;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(54, 100, 175, 0.2);
        }

        .btn-csa-success {
            background-color: var(--csa-green);
            color: #ffffff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            border: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-csa-success:hover {
            background-color: #3aa03f;
            color: #ffffff;
        }

        .btn-solid-light {
            background-color: var(--surface);
            color: var(--text-muted);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            border: 1px solid var(--border-hard);
            text-decoration: none;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .btn-solid-light:hover {
            background-color: #F1F5F9;
            color: var(--text-main);
        }

        /* Tables */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            overflow: hidden;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .custom-table th {
            background-color: #F8FAFC;
            color: #64748B;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-soft);
            white-space: nowrap;
        }

        .custom-table td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-soft);
            color: var(--text-main);
            font-size: 0.9rem;
        }

        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: #F8FAFC;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-id {
            font-family: 'JetBrains Mono', monospace;
            color: var(--csa-blue);
            font-size: 0.8rem;
            font-weight: 600;
            background: #EFF6FF;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #DBEAFE;
        }

        .data-name {
            font-weight: 700;
            color: var(--text-main);
            font-size: 0.95rem;
        }

        .data-description {
            color: var(--text-muted);
            max-width: 450px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .action-group {
            display: flex;
            gap: 10px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            background-color: var(--bg-body);
        }

        .btn-icon.edit {
            color: var(--csa-blue);
        }

        .btn-icon.edit:hover {
            background-color: #EFF6FF;
            border-color: #BFDBFE;
        }

        .btn-icon.delete {
            color: #EF4444;
        }

        .btn-icon.delete:hover {
            background-color: #FEF2F2;
            border-color: #FECACA;
        }

        .empty-state {
            padding: 64px 24px;
            text-align: center;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            background: #F8FAFC;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-state-icon i {
            font-size: 1.8rem;
            color: #94A3B8;
        }

        .empty-state-text {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .empty-state-subtext {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .pagination-wrapper {
            padding: 20px 24px;
            border-top: 1px solid var(--border-soft);
            background: var(--surface);
        }

        .pagination {
            margin: 0;
            gap: 6px;
        }

        .page-item .page-link {
            color: var(--text-muted);
            border: 1px solid var(--border-soft);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .page-item .page-link:hover {
            background-color: #F8FAFC;
            color: var(--text-main);
        }

        .page-item.active .page-link {
            background-color: var(--csa-blue);
            border-color: var(--csa-blue);
            color: white;
        }

        /* Forms */
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 32px;
            max-width: 800px;
        }

        .custom-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
            display: block;
        }

        .custom-input,
        .custom-textarea {
            width: 100%;
            background-color: #F8FAFC;
            border: 1px solid var(--border-soft);
            color: var(--text-main);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .custom-input:focus,
        .custom-textarea:focus {
            outline: none;
            background-color: var(--surface);
            border-color: var(--csa-blue);
            box-shadow: 0 0 0 3px rgba(54, 100, 175, 0.1);
        }

        .custom-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-soft);
        }

        /* =========================================================
           4. ALERTS & TOASTS (Sonner Style)
           ========================================================= */
        .sonner-toast {
            position: fixed;
            bottom: 32px;
            right: 32px;
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04);
            z-index: 9999;
            transform: translateY(100px) scale(0.9);
            opacity: 0;
            animation: sonnerEnter 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            max-width: 350px;
        }

        .sonner-toast.hide {
            animation: sonnerLeave 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .toast-icon.success i {
            color: var(--csa-green);
            font-size: 1.25rem;
            margin-top: 2px;
        }

        .toast-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .toast-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .toast-description {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        @keyframes sonnerEnter {
            0% {
                transform: translateY(30px) scale(0.9);
                opacity: 0;
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        @keyframes sonnerLeave {
            0% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }

            100% {
                transform: translateY(30px) scale(0.9);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-layer-group me-2"></i>
            CSA Admin Panel
        </div>

        <div class="sidebar-content">
            <div class="menu-header">Analytics</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            @if (auth()->user()->role->slug === 'super-admin')
                <div class="menu-header">Master Data</div>
                <a href="{{ route('admin.divisions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Divisions
                </a>
                <a href="{{ route('admin.positions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.positions.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i> Positions
                </a>
                <a href="{{ route('admin.settings.edit') }}"
                    class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            @endif

            @if (in_array(auth()->user()->role->slug, ['super-admin', 'sekretaris']))
                <div class="menu-header">Publication</div>
                <a href="{{ route('admin.news-categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.news-categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="{{ route('admin.news.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> News
                </a>
                <a href="{{ route('admin.documents.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> Documents
                </a>
            @endif

            @if (in_array(auth()->user()->role->slug, ['super-admin', 'bendahara']))
                <div class="menu-header">Finance</div>
                <a href="{{ route('admin.incomes.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.incomes.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-trend-up"></i> Incomes
                </a>
                <a href="{{ route('admin.expenses.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-trend-down"></i> Expenses
                </a>
            @endif

            @if (in_array(auth()->user()->role->slug, ['super-admin', 'kepala-divisi']))
                <div class="menu-header">Operational</div>
                <a href="{{ route('admin.members.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Members
                </a>
                <a href="{{ route('admin.events.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Events
                </a>
                <a href="{{ route('admin.event-registrations.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.event-registrations.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Registrations
                </a>
                <a href="{{ route('admin.attendances.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Attendances
                </a>
            @endif

            @if (in_array(auth()->user()->role->slug, ['super-admin', 'ketua']))
                <div class="menu-header">Media</div>
                <a href="{{ route('admin.albums.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.albums.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> Albums
                </a>
                <a href="{{ route('admin.galleries.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="fas fa-photo-video"></i> Galleries
                </a>
            @endif
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="profile-box">
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->name }}</div>
                    <div class="profile-role">{{ str_replace('-', ' ', auth()->user()->role->slug) }}</div>
                </div>
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="height: 30px; width: 1px; background: var(--border-soft); margin: 0 8px;"></div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @if (session('success'))
        <div id="csa-sonner-toast" class="sonner-toast" role="alert">
            <div class="toast-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <p class="toast-title">Berhasil</p>
                <p class="toast-description">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-body p-4 p-md-5 text-center">
                    <div
                        style="width: 72px; height: 72px; background: #FEF2F2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #EF4444; font-size: 1.8rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 style="font-weight: 800; color: var(--text-main); margin-bottom: 12px;">Konfirmasi Penghapusan
                    </h4>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 32px;">
                        Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus
                        data ini dari sistem?
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn-solid-light m-0" data-bs-dismiss="modal">Batalkan</button>
                        <button type="button" class="btn-csa-primary m-0"
                            style="background-color: #EF4444; box-shadow: none;" id="confirmDeleteBtn">
                            <i class="fas fa-trash me-2"></i> Ya, Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Logic Sonner Toast
            const toast = document.getElementById('csa-sonner-toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 400);
                }, 4000);
            }

            // Logic Global Delete Modal
            const deleteModalElement = document.getElementById('globalDeleteModal');
            if (deleteModalElement) {
                const deleteModal = new bootstrap.Modal(deleteModalElement);
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                let currentFormToSubmit = null;

                document.querySelectorAll('.trigger-delete').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentFormToSubmit = this.closest('form');
                        deleteModal.show();
                    });
                });

                confirmBtn.addEventListener('click', function() {
                    if (currentFormToSubmit) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
                        this.style.opacity = '0.7';
                        this.style.pointerEvents = 'none';
                        currentFormToSubmit.submit();
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
