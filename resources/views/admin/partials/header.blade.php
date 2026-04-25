<header class="admin-header">
    <style>
        .topbar-actions{display:flex; align-items:center; gap:10px;}
        .topbar-btn{height:38px; width:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; border:1px solid rgba(17,24,39,0.10); background:#fff; color:#111827; position:relative;}
        .topbar-btn:hover{background:rgba(17,24,39,0.03);}
        .topbar-badge{position:absolute; top:-6px; right:-6px; min-width:18px; height:18px; border-radius:999px; padding:0 6px; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; background:#ef4444; color:#fff; border:2px solid #fff;}
        .topbar-menu{position:relative;}
        .topbar-dropdown{position:absolute; right:0; top:44px; width:290px; background:#fff; border:1px solid rgba(17,24,39,0.10); border-radius:14px; box-shadow:0 16px 40px rgba(0,0,0,0.10); overflow:hidden; z-index:50; display:none;}
        .topbar-dropdown.open{display:block;}
        .topbar-dd-head{padding:12px 14px; border-bottom:1px solid rgba(17,24,39,0.08); font-weight:800;}
        .topbar-dd-body{padding:10px 14px;}
        .topbar-dd-item{display:flex; align-items:center; gap:10px; padding:10px 10px; border-radius:12px; color:#111827; text-decoration:none;}
        .topbar-dd-item:hover{background:rgba(17,24,39,0.04);}
        .topbar-dd-ico{width:30px; height:30px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:rgba(37,99,235,0.12); color:#2563eb;}
        .user-chip{display:flex; align-items:center; gap:10px; padding:6px 10px; border-radius:14px; border:1px solid rgba(17,24,39,0.10); background:#fff; cursor:pointer;}
        .user-avatar{width:38px; height:38px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-weight:900; background:linear-gradient(135deg, rgba(37,99,235,0.18), rgba(168,85,247,0.18)); color:#111827;}
        .user-meta{display:flex; flex-direction:column; line-height:1.05;}
        .user-meta .user-name{font-weight:900; font-size:13px;}
        .user-meta .user-role{font-size:12px; color:#6b7280; font-weight:700;}
        .dd-slim{width:260px;}
        .dd-muted{color:#6b7280; font-size:12px;}
        .dd-row{display:flex; align-items:center; justify-content:space-between;}
        .dd-sep{height:1px; background:rgba(17,24,39,0.08); margin:8px 0;}
    </style>
    <div class="header-left">
        <button id="toggleSidebar" class="sidebar-toggle">
            <svg viewBox="0 0 24 24" fill="none" width="24" height="24" stroke="currentColor" stroke-width="2"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        </button>
        <h2 class="page-title">@yield('title', 'Dashboard')</h2>
    </div>
    <div class="header-right">
        <div class="topbar-actions">
            <div class="topbar-menu" data-dd>
                <button type="button" class="topbar-btn" data-dd-btn aria-label="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" width="20" height="20" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span class="topbar-badge" style="display:none;" data-notif-badge>0</span>
                </button>
                <div class="topbar-dropdown" data-dd-panel>
                    <div class="topbar-dd-head">
                        <div class="dd-row">
                            <span>Notifications</span>
                            <span class="dd-muted">0 new</span>
                        </div>
                    </div>
                    <div class="topbar-dd-body">
                        <div class="dd-muted" style="padding:6px 2px;">No notifications yet.</div>
                    </div>
                </div>
            </div>

            <div class="topbar-menu" data-dd>
                <button type="button" class="user-chip" data-dd-btn aria-label="User menu">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="user-meta">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ Auth::user()->is_admin ? 'Administrator' : 'User' }}</span>
                    </div>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="topbar-dropdown dd-slim" data-dd-panel>
                    <div class="topbar-dd-head">Account</div>
                    <div class="topbar-dd-body">
                        <a class="topbar-dd-item" href="{{ route('home') }}">
                            <span class="topbar-dd-ico"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                            <span>My Profile</span>
                        </a>
                        <a class="topbar-dd-item" href="{{ route('admin.settings.general') }}">
                            <span class="topbar-dd-ico" style="background:rgba(16,185,129,0.12); color:#10b981;"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v2"/><path d="M12 21v2"/><path d="M4.22 4.22l1.42 1.42"/><path d="M18.36 18.36l1.42 1.42"/><path d="M1 12h2"/><path d="M21 12h2"/><path d="M4.22 19.78l1.42-1.42"/><path d="M18.36 5.64l1.42-1.42"/><circle cx="12" cy="12" r="4"/></svg></span>
                            <span>Settings</span>
                        </a>
                        <div class="dd-sep"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="topbar-dd-item" style="width:100%; border:0; background:transparent; text-align:left; cursor:pointer;">
                                <span class="topbar-dd-ico" style="background:rgba(239,68,68,0.10); color:#ef4444;"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg></span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
(() => {
    const closeAll = () => {
        document.querySelectorAll('[data-dd-panel].open').forEach(p => p.classList.remove('open'));
    };

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-dd-btn]');
        if (!btn) {
            if (!e.target.closest('[data-dd]')) closeAll();
            return;
        }

        const root = btn.closest('[data-dd]');
        const panel = root ? root.querySelector('[data-dd-panel]') : null;
        if (!panel) return;

        const isOpen = panel.classList.contains('open');
        closeAll();
        if (!isOpen) panel.classList.add('open');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });
})();
</script>
