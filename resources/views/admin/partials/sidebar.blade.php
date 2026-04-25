<aside class="admin-sidebar">
    <div class="admin-brand">
        @php
            $siteLogo = \App\Models\SystemSetting::query()->where('key', 'site.logo_url')->value('value');
            $siteName = \App\Models\SystemSetting::query()->where('key', 'site.name')->value('value') ?: 'Mamacare AI';
        @endphp
        <img src="{{ $siteLogo ?: asset('meetup_3669956.png') }}" alt="Logo" class="admin-logo-img">
        <div class="brand-text">
            <span class="m-text">{{ $siteName }}</span>
            <span class="k-text">Admin Panel</span>
        </div>
    </div>

    <nav class="admin-nav custom-scrollbar">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span>Dashboard</span>
        </a>

        <div class="nav-section">Accounting</div>
        <a href="{{ route('admin.vouchers') }}" class="admin-nav-link {{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>Vouchers</span>
        </a>
        <a href="{{ route('admin.chart-of-accounts') }}" class="admin-nav-link {{ request()->routeIs('admin.chart-of-accounts') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            <span>Accounts</span>
        </a>
        <a href="{{ route('admin.banks') }}" class="admin-nav-link {{ request()->routeIs('admin.banks') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M3 10L12 3l9 7"/><rect x="5" y="10" width="3" height="8"/><rect x="10.5" y="10" width="3" height="8"/><rect x="16" y="10" width="3" height="8"/><path d="M2 18h20"/></svg>
            <span>Banks</span>
        </a>

        <div class="nav-section">Mothers & Content</div>
        <a href="{{ route('admin.mothers') }}" class="admin-nav-link {{ request()->routeIs('admin.mothers') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Mothers Intake</span>
        </a>
        <a href="{{ route('admin.articles') }}" class="admin-nav-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8l-4 4v14a2 2 0 0 0 2 2z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            <span>Articles</span>
        </a>

        <div class="nav-section">ERP System</div>
        <a href="{{ route('admin.elms.courses') }}" class="admin-nav-link {{ request()->routeIs('admin.elms.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M8 7h8"/><path d="M8 11h8"/><path d="M8 15h5"/></svg>
            <span>ELMS Courses</span>
        </a>
        <a href="{{ route('admin.elms.articles') }}" class="admin-nav-link {{ request()->routeIs('admin.elms.articles*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>ELMS Articles</span>
        </a>
        <a href="{{ route('admin.elms.categories') }}" class="admin-nav-link {{ request()->routeIs('admin.elms.categories*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
            <span>ELMS Categories</span>
        </a>
        <a href="{{ route('admin.elms.trainers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.elms.trainers*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>ELMS Trainers</span>
        </a>
        <div class="nav-dropdown {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"/><path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"/><rect width="20" height="8" x="2" y="8" rx="2"/></svg>
                <span>Inventory</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.inventory') }}" class="{{ request()->routeIs('admin.inventory') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.inventory.stock-levels') }}" class="{{ request()->routeIs('admin.inventory.stock-levels') ? 'active' : '' }}">Stock Levels</a>
                <a href="{{ route('admin.inventory.products') }}" class="{{ request()->routeIs('admin.inventory.products') ? 'active' : '' }}">Products</a>
                <a href="{{ route('admin.inventory.suppliers') }}" class="{{ request()->routeIs('admin.inventory.suppliers') ? 'active' : '' }}">Suppliers</a>
            </div>
        </div>
        <div class="nav-dropdown {{ request()->routeIs('admin.sales*') || request()->routeIs('admin.expenses*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m17 19-5 3-5-3"/><rect width="20" height="12" x="2" y="6" rx="2"/></svg>
                <span>Sales & Finance</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.sales') }}" class="{{ request()->routeIs('admin.sales') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.sales.invoices') }}" class="{{ request()->routeIs('admin.sales.invoices') ? 'active' : '' }}">Invoices</a>
                <a href="{{ route('admin.sales.payments') }}" class="{{ request()->routeIs('admin.sales.payments') ? 'active' : '' }}">Payments</a>
                <a href="{{ route('admin.sales.cash-sale') }}" class="{{ request()->routeIs('admin.sales.cash-sale') ? 'active' : '' }}">Cash Sale</a>
                <a href="{{ route('admin.sales.sales-invoice') }}" class="{{ request()->routeIs('admin.sales.sales-invoice') ? 'active' : '' }}">Sales Invoice</a>
                <a href="{{ route('admin.sales.day-book') }}" class="{{ request()->routeIs('admin.sales.day-book') ? 'active' : '' }}">Day Book</a>
                <a href="{{ route('admin.sales.register') }}" class="{{ request()->routeIs('admin.sales.register') ? 'active' : '' }}">Register</a>
                <a href="{{ route('admin.sales.return') }}" class="{{ request()->routeIs('admin.sales.return') ? 'active' : '' }}">Sales Return</a>
                <a href="{{ route('admin.sales.quotation') }}" class="{{ request()->routeIs('admin.sales.quotation') ? 'active' : '' }}">Quotation</a>
                <a href="{{ route('admin.sales.debit-note') }}" class="{{ request()->routeIs('admin.sales.debit-note') ? 'active' : '' }}">Debit Note</a>
                <a href="{{ route('admin.sales.credit-note') }}" class="{{ request()->routeIs('admin.sales.credit-note') ? 'active' : '' }}">Credit Note</a>
                <a href="{{ route('admin.expenses') }}" class="{{ request()->routeIs('admin.expenses') ? 'active' : '' }}">Expenses</a>
            </div>
        </div>

        <a href="{{ route('admin.customers') }}" class="admin-nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Customers</span>
        </a>
        <a href="{{ route('admin.suppliers') }}" class="admin-nav-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Suppliers</span>
        </a>
        <a href="{{ route('admin.imports.import-order') }}" class="admin-nav-link {{ request()->routeIs('admin.imports.import-order') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
            <span>Imports</span>
        </a>
        <a href="{{ route('admin.bundles') }}" class="admin-nav-link {{ request()->routeIs('admin.bundles') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            <span>Bundles</span>
        </a>

        <div class="nav-section">Reports</div>
        <div class="nav-dropdown {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span>Reports</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.reports.pnl') }}" class="{{ request()->routeIs('admin.reports.pnl') ? 'active' : '' }}">P&amp;L</a>
                <a href="{{ route('admin.reports.trial-balance') }}" class="{{ request()->routeIs('admin.reports.trial-balance') ? 'active' : '' }}">Trial Balance</a>
                <a href="{{ route('admin.reports.balance-sheet') }}" class="{{ request()->routeIs('admin.reports.balance-sheet') ? 'active' : '' }}">Balance Sheet</a>
                <a href="{{ route('admin.reports.ar-aging') }}" class="{{ request()->routeIs('admin.reports.ar-aging') ? 'active' : '' }}">AR Aging</a>
                <a href="{{ route('admin.reports.ap-aging') }}" class="{{ request()->routeIs('admin.reports.ap-aging') ? 'active' : '' }}">AP Aging</a>
                <a href="{{ route('admin.reports.stock-valuation') }}" class="{{ request()->routeIs('admin.reports.stock-valuation') ? 'active' : '' }}">Stock Valuation</a>
                <a href="{{ route('admin.reports.purchase-register') }}" class="{{ request()->routeIs('admin.reports.purchase-register') ? 'active' : '' }}">Purchase Register</a>
                <a href="{{ route('admin.reports.payment-register') }}" class="{{ request()->routeIs('admin.reports.payment-register') ? 'active' : '' }}">Payment Register</a>
                <a href="{{ route('admin.reports.stock-transfer-register') }}" class="{{ request()->routeIs('admin.reports.stock-transfer-register') ? 'active' : '' }}">Stock Transfer Register</a>
            </div>
        </div>

        <div class="nav-section">Investors</div>
        <div class="nav-dropdown {{ request()->routeIs('admin.investors*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                <span>Investors</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.investors') }}" class="{{ request()->routeIs('admin.investors') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.investors.hub') }}" class="{{ request()->routeIs('admin.investors.hub') ? 'active' : '' }}">Hub</a>
                <a href="{{ route('admin.investors.portfolio') }}" class="{{ request()->routeIs('admin.investors.portfolio') ? 'active' : '' }}">Portfolio</a>
                <a href="{{ route('admin.investors.reports') }}" class="{{ request()->routeIs('admin.investors.reports') ? 'active' : '' }}">Reports</a>
            </div>
        </div>

        <div class="nav-section">CRM</div>
        <div class="nav-dropdown {{ request()->routeIs('admin.crm*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>
                <span>CRM</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.crm.hub') }}" class="{{ request()->routeIs('admin.crm.hub') ? 'active' : '' }}">Hub</a>
                <a href="{{ route('admin.crm.inbox') }}" class="{{ request()->routeIs('admin.crm.inbox') ? 'active' : '' }}">Inbox</a>
                <a href="{{ route('admin.crm.automations') }}" class="{{ request()->routeIs('admin.crm.automations') ? 'active' : '' }}">Automations</a>
                <a href="{{ route('admin.crm.preorders') }}" class="{{ request()->routeIs('admin.crm.preorders') ? 'active' : '' }}">Pre-Orders</a>
                <a href="{{ route('admin.crm.referrals') }}" class="{{ request()->routeIs('admin.crm.referrals') ? 'active' : '' }}">Referrals</a>
                <a href="{{ route('admin.crm.loyalty') }}" class="{{ request()->routeIs('admin.crm.loyalty') ? 'active' : '' }}">Loyalty</a>
                <a href="{{ route('admin.crm.feedback') }}" class="{{ request()->routeIs('admin.crm.feedback') ? 'active' : '' }}">Feedback</a>
                <a href="{{ route('admin.crm.upsell') }}" class="{{ request()->routeIs('admin.crm.upsell') ? 'active' : '' }}">Upsell</a>
                <a href="{{ route('admin.crm.customers') }}" class="{{ request()->routeIs('admin.crm.customers') ? 'active' : '' }}">Customers</a>
            </div>
        </div>

        <div class="nav-section">HRM & Operations</div>
        <div class="nav-dropdown {{ request()->routeIs('admin.employees*') || request()->routeIs('admin.payroll*') || request()->routeIs('admin.attendance*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Human Resource</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.employees') }}" class="{{ request()->routeIs('admin.employees') ? 'active' : '' }}">Employees</a>
                <a href="{{ route('admin.payroll') }}" class="{{ request()->routeIs('admin.payroll') ? 'active' : '' }}">Payroll</a>
                <a href="{{ route('admin.attendance') }}" class="{{ request()->routeIs('admin.attendance') ? 'active' : '' }}">Attendance</a>
            </div>
        </div>

        <div class="nav-dropdown {{ request()->routeIs('admin.hrm*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>HRM</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.hrm') }}" class="{{ request()->routeIs('admin.hrm') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.hrm.employees') }}" class="{{ request()->routeIs('admin.hrm.employees') ? 'active' : '' }}">Employees</a>
                <a href="{{ route('admin.hrm.assets') }}" class="{{ request()->routeIs('admin.hrm.assets') ? 'active' : '' }}">Assets</a>
                <a href="{{ route('admin.hrm.payroll') }}" class="{{ request()->routeIs('admin.hrm.payroll') ? 'active' : '' }}">Payroll</a>
                <a href="{{ route('admin.hrm.payslips') }}" class="{{ request()->routeIs('admin.hrm.payslips') ? 'active' : '' }}">Payslips</a>
                <a href="{{ route('admin.hrm.leave') }}" class="{{ request()->routeIs('admin.hrm.leave') ? 'active' : '' }}">Leave</a>
                <a href="{{ route('admin.hrm.attendance') }}" class="{{ request()->routeIs('admin.hrm.attendance') ? 'active' : '' }}">Attendance</a>
                <a href="{{ route('admin.hrm.performance') }}" class="{{ request()->routeIs('admin.hrm.performance') ? 'active' : '' }}">Performance</a>
                <a href="{{ route('admin.hrm.recruitment') }}" class="{{ request()->routeIs('admin.hrm.recruitment') ? 'active' : '' }}">Recruitment</a>
                <a href="{{ route('admin.hrm.events') }}" class="{{ request()->routeIs('admin.hrm.events') ? 'active' : '' }}">Events</a>
                <a href="{{ route('admin.hrm.settings') }}" class="{{ request()->routeIs('admin.hrm.settings') ? 'active' : '' }}">Settings</a>
            </div>
        </div>

        <div class="nav-dropdown {{ request()->routeIs('admin.pos*') || request()->routeIs('admin.inventory.products*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="M6 8h.01"/><path d="M10 8h.01"/><path d="M14 8h.01"/><path d="M18 8h.01"/><path d="M8 12h8"/><path d="M12 12v4"/><path d="M7 16h10"/></svg>
                <span>POS System</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.pos') }}" class="{{ request()->routeIs('admin.pos') ? 'active' : '' }}">New Sale</a>
                <a href="{{ route('admin.pos.orders') }}" class="{{ request()->routeIs('admin.pos.orders') ? 'active' : '' }}">Orders & Receipts</a>
                <a href="{{ route('admin.inventory.products') }}" class="{{ request()->routeIs('admin.inventory.products*') ? 'active' : '' }}">Products</a>
                <a href="{{ route('admin.bundles') }}" class="{{ request()->routeIs('admin.bundles') ? 'active' : '' }}">Bundles</a>
            </div>
        </div>

        <a href="{{ route('admin.data-import') }}" class="admin-nav-link {{ request()->routeIs('admin.data-import') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <span>Data Import</span>
        </a>

        <div class="nav-section">Settings</div>
        <div class="nav-dropdown {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <button class="nav-dropdown-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Settings</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" width="14" height="14" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dropdown-content">
                <a href="{{ route('admin.settings.general') }}" class="{{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">General</a>
                <a href="{{ route('admin.settings.users') }}" class="{{ request()->routeIs('admin.settings.users') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.settings.approvals') }}" class="{{ request()->routeIs('admin.settings.approvals') ? 'active' : '' }}">Approvals</a>
                <a href="{{ route('admin.settings.accounting') }}" class="{{ request()->routeIs('admin.settings.accounting') ? 'active' : '' }}">Accounting</a>
                <a href="{{ route('admin.settings.whatsapp') }}" class="{{ request()->routeIs('admin.settings.whatsapp') ? 'active' : '' }}">WhatsApp</a>
                <a href="{{ route('admin.settings.location') }}" class="{{ request()->routeIs('admin.settings.location') ? 'active' : '' }}">Location</a>
                <a href="{{ route('admin.settings.inventory') }}" class="{{ request()->routeIs('admin.settings.inventory') ? 'active' : '' }}">Inventory</a>
                <a href="{{ route('admin.settings.receipt-template') }}" class="{{ request()->routeIs('admin.settings.receipt-template') ? 'active' : '' }}">Receipt Template</a>
                <a href="{{ route('admin.settings.invoice-template') }}" class="{{ request()->routeIs('admin.settings.invoice-template') ? 'active' : '' }}">Invoice Template</a>
                <a href="{{ route('admin.settings.report-templates') }}" class="{{ request()->routeIs('admin.settings.report-templates') ? 'active' : '' }}">Report Templates</a>
                <a href="{{ route('admin.settings.display') }}" class="{{ request()->routeIs('admin.settings.display') ? 'active' : '' }}">Display</a>
                <a href="{{ route('admin.settings.pricelist-template') }}" class="{{ request()->routeIs('admin.settings.pricelist-template') ? 'active' : '' }}">Pricelist Template</a>
            </div>
        </div>

        <div class="nav-section">Communication</div>
        <a href="{{ route('admin.messaging') }}" class="admin-nav-link {{ request()->routeIs('admin.messaging*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Messaging</span>
        </a>
        <a href="{{ route('admin.media.index') }}" class="admin-nav-link {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Media Manager</span>
        </a>
        <span class="badge-count">3</span>
        </a>

        <div class="nav-divider"></div>
        <a href="{{ route('admin.services') }}" class="admin-nav-link {{ request()->routeIs('admin.services') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span>Services</span>
        </a>
        <a href="{{ route('admin.konnect') }}" class="admin-nav-link {{ request()->routeIs('admin.konnect') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            <span>Konnect</span>
        </a>
    </nav>

    <div class="admin-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<script>
document.querySelectorAll('.nav-dropdown-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const parent = btn.parentElement;
        parent.classList.toggle('active');
    });
});
</script>
