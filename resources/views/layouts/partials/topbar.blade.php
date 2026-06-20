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