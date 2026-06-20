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