<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.landing');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/privacy', function () {
    return view('landing.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('landing.terms');
})->name('terms');

Route::get('/legal', function () {
    return view('landing.legal');
})->name('legal');

Route::get('/about', function () {
    return view('landing.landing');
})->name('about');

Route::get('/articles', function () {
    $articles = DB::table('articles')->orderBy('published_at', 'desc')->get();
    return view('landing.articles_list', compact('articles'));
})->name('articles');

Route::get('/articles/{slug}', function ($slug) {
    $article = DB::table('articles')->where('slug', $slug)->first();
    if (!$article) abort(404);
    return view('landing.article_details', compact('article'));
})->name('articles.show');

Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::post('/webhooks/whatsapp', [App\Http\Controllers\WhatsappWebhookController::class, 'handle'])->name('webhooks.whatsapp');

Route::get('/categories', function () {
    $categories = DB::table('article_categories')
        ->select('article_categories.*', DB::raw('(SELECT COUNT(*) FROM articles WHERE articles.category_id = article_categories.id) as articles_count'))
        ->get();
    return view('landing.categories_list', compact('categories'));
})->name('categories');

Route::get('/articles/category/{slug}', function ($slug) {
    $category = DB::table('article_categories')->where('slug', $slug)->first();
    if (!$category) abort(404);
    $articles = DB::table('articles')->where('category_id', $category->id)->orderBy('published_at', 'desc')->get();
    return view('landing.articles_list', compact('articles', 'category'));
})->name('articles.category');

Route::get('/join', function () {
    return view('landing.join');
})->name('join');

Route::post('/join', [App\Http\Controllers\MotherController::class, 'store'])->name('join.store');
Route::get('/join/thanks', [App\Http\Controllers\MotherController::class, 'thanks'])->name('join.thanks');

Route::get('/api/regions/{region}/districts', function ($regionId) {
    return App\Models\District::where('region_id', $regionId)->orderBy('name')->get();
});

Route::get('/api/mothers/approved', [App\Http\Controllers\Api\MotherIntakeController::class, 'approved'])->name('api.mothers.approved');

// WhatsApp Webhook Routes
Route::group(['prefix' => 'api/webhooks', 'as' => 'webhooks.'], function () {
    Route::post('/whatsapp-status', [App\Http\Controllers\Api\WhatsappWebhookController::class, 'handleStatus'])->name('whatsapp-status');
    Route::post('/whatsapp-incoming', [App\Http\Controllers\Api\WhatsappWebhookController::class, 'handleIncoming'])->name('whatsapp-incoming');
    Route::get('/whatsapp-stats', [App\Http\Controllers\Api\WhatsappWebhookController::class, 'stats'])->name('whatsapp-stats');
});

// Mother Authentication Routes (Public)
Route::group(['prefix' => 'mother', 'as' => 'mother.'], function () {
    Route::get('/login', [App\Http\Controllers\MotherAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\MotherAuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\MotherAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\MotherAuthController::class, 'register']);
    Route::get('/forgot-password', [App\Http\Controllers\MotherAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [App\Http\Controllers\MotherAuthController::class, 'forgotPassword']);
});

// Social Authentication Routes
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/google', [App\Http\Controllers\MotherAuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [App\Http\Controllers\MotherAuthController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::get('/apple', [App\Http\Controllers\MotherAuthController::class, 'redirectToApple'])->name('apple');
    Route::get('/apple/callback', [App\Http\Controllers\MotherAuthController::class, 'handleAppleCallback'])->name('apple.callback');
});

// Mother Dashboard Routes (Protected)
Route::group(['prefix' => 'mother', 'middleware' => ['auth'], 'as' => 'mother.'], function () {
    Route::post('/logout', [App\Http\Controllers\MotherAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [App\Http\Controllers\MotherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\MotherDashboardController::class, 'profile'])->name('profile');
    
    // Appointments
    Route::get('/appointments', [App\Http\Controllers\MotherDashboardController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [App\Http\Controllers\MotherDashboardController::class, 'storeAppointment'])->name('appointments.store');
    
    // Health Data
    Route::get('/health-data', [App\Http\Controllers\MotherDashboardController::class, 'healthData'])->name('health-data');
    Route::post('/health-data/weight', [App\Http\Controllers\MotherDashboardController::class, 'storeWeight'])->name('health-data.weight');
    Route::post('/health-data/bp', [App\Http\Controllers\MotherDashboardController::class, 'storeBloodPressure'])->name('health-data.bp');
    Route::post('/health-data/kicks', [App\Http\Controllers\MotherDashboardController::class, 'storeKickCount'])->name('health-data.kicks');
    
    // Alerts
    Route::get('/alerts', [App\Http\Controllers\MotherDashboardController::class, 'alerts'])->name('alerts');
    Route::post('/alerts/{alert}/read', [App\Http\Controllers\MotherDashboardController::class, 'markAlertRead'])->name('alerts.read');
    
    // Checklist
    Route::get('/checklist', [App\Http\Controllers\MotherDashboardController::class, 'checklist'])->name('checklist');
    Route::post('/checklist/{item}/toggle', [App\Http\Controllers\MotherDashboardController::class, 'toggleChecklistItem'])->name('checklist.toggle');
    
    // Daily Log
    Route::get('/daily-log', [App\Http\Controllers\MotherDashboardController::class, 'dailyLog'])->name('daily-log');
    Route::post('/daily-log', [App\Http\Controllers\MotherDashboardController::class, 'storeDailyLog'])->name('daily-log.store');
    
    // Education & Emergency
    Route::get('/education', [App\Http\Controllers\MotherDashboardController::class, 'education'])->name('education');
    Route::get('/emergency', [App\Http\Controllers\MotherDashboardController::class, 'emergency'])->name('emergency');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', 'admin_login_activity'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Reference Admin Menu (placeholders)
    Route::get('/vouchers', [App\Http\Controllers\Admin\VouchersController::class, 'index'])->name('vouchers');
    Route::get('/vouchers/cash-payment', [App\Http\Controllers\Admin\VouchersController::class, 'cashPaymentCreate'])->name('vouchers.cash-payment.create');
    Route::post('/vouchers/cash-payment', [App\Http\Controllers\Admin\VouchersController::class, 'cashPaymentStore'])->name('vouchers.cash-payment.store');
    Route::get('/vouchers/purchase-invoice', [App\Http\Controllers\Admin\VouchersController::class, 'purchaseInvoiceCreate'])->name('vouchers.purchase-invoice.create');
    Route::post('/vouchers/purchase-invoice', [App\Http\Controllers\Admin\VouchersController::class, 'purchaseInvoiceStore'])->name('vouchers.purchase-invoice.store');
    Route::get('/vouchers/cash-receipt', [App\Http\Controllers\Admin\VouchersController::class, 'cashReceiptCreate'])->name('vouchers.cash-receipt.create');
    Route::post('/vouchers/cash-receipt', [App\Http\Controllers\Admin\VouchersController::class, 'cashReceiptStore'])->name('vouchers.cash-receipt.store');
    Route::get('/vouchers/bank-transfer', [App\Http\Controllers\Admin\VouchersController::class, 'bankTransferCreate'])->name('vouchers.bank-transfer.create');
    Route::post('/vouchers/bank-transfer', [App\Http\Controllers\Admin\VouchersController::class, 'bankTransferStore'])->name('vouchers.bank-transfer.store');
    Route::get('/vouchers/purchase-return', [App\Http\Controllers\Admin\VouchersController::class, 'purchaseReturnCreate'])->name('vouchers.purchase-return.create');
    Route::post('/vouchers/purchase-return', [App\Http\Controllers\Admin\VouchersController::class, 'purchaseReturnStore'])->name('vouchers.purchase-return.store');
    Route::get('/vouchers/contra-entry', [App\Http\Controllers\Admin\VouchersController::class, 'contraEntryCreate'])->name('vouchers.contra-entry.create');
    Route::post('/vouchers/contra-entry', [App\Http\Controllers\Admin\VouchersController::class, 'contraEntryStore'])->name('vouchers.contra-entry.store');
    Route::get('/vouchers/credit-note', [App\Http\Controllers\Admin\VouchersController::class, 'creditNoteCreate'])->name('vouchers.credit-note.create');
    Route::post('/vouchers/credit-note', [App\Http\Controllers\Admin\VouchersController::class, 'creditNoteStore'])->name('vouchers.credit-note.store');
    Route::get('/vouchers/sales-invoice', [App\Http\Controllers\Admin\VouchersController::class, 'salesInvoiceCreate'])->name('vouchers.sales-invoice.create');
    Route::post('/vouchers/sales-invoice', [App\Http\Controllers\Admin\VouchersController::class, 'salesInvoiceStore'])->name('vouchers.sales-invoice.store');
    Route::get('/vouchers/{voucher}/view', [App\Http\Controllers\Admin\VouchersController::class, 'view'])->name('vouchers.view');
    Route::get('/accounts/chart-of-accounts', [App\Http\Controllers\Admin\AccountsController::class, 'index'])->name('chart-of-accounts');
    Route::get('/accounts/create', [App\Http\Controllers\Admin\AccountsController::class, 'create'])->name('accounts.create');
    Route::post('/accounts', [App\Http\Controllers\Admin\AccountsController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{account}/edit', [App\Http\Controllers\Admin\AccountsController::class, 'edit'])->name('accounts.edit');
    Route::put('/accounts/{account}', [App\Http\Controllers\Admin\AccountsController::class, 'update'])->name('accounts.update');

    Route::get('/journals', [App\Http\Controllers\Admin\JournalsController::class, 'index'])->name('journals');
    Route::get('/journals/{journal}', [App\Http\Controllers\Admin\JournalsController::class, 'show'])->name('journals.show');

    Route::get('/banks', [App\Http\Controllers\Admin\BanksController::class, 'index'])->name('banks');

    // Sales (Reference)
    Route::get('/sales/cash-sale', [App\Http\Controllers\Admin\SalesPagesController::class, 'cashSale'])->name('sales.cash-sale');
    Route::get('/sales/sales-invoice', [App\Http\Controllers\Admin\SalesPagesController::class, 'salesInvoice'])->name('sales.sales-invoice');
    Route::get('/sales/day-book', [App\Http\Controllers\Admin\SalesPagesController::class, 'dayBook'])->name('sales.day-book');
    Route::get('/sales/register', [App\Http\Controllers\Admin\SalesPagesController::class, 'register'])->name('sales.register');
    Route::get('/sales/sales-return', [App\Http\Controllers\Admin\SalesPagesController::class, 'salesReturn'])->name('sales.return');
    Route::get('/sales/quotation', [App\Http\Controllers\Admin\SalesPagesController::class, 'quotation'])->name('sales.quotation');
    Route::post('/sales/quotation', [App\Http\Controllers\Admin\SalesPagesController::class, 'quotationStore'])->name('sales.quotation.store');
    Route::get('/sales/debit-note', [App\Http\Controllers\Admin\SalesPagesController::class, 'debitNote'])->name('sales.debit-note');
    Route::post('/sales/debit-note', [App\Http\Controllers\Admin\SalesPagesController::class, 'debitNoteStore'])->name('sales.debit-note.store');
    Route::get('/sales/credit-note', [App\Http\Controllers\Admin\SalesPagesController::class, 'creditNote'])->name('sales.credit-note');

    Route::get('/customers', [App\Http\Controllers\Admin\CustomersController::class, 'index'])->name('customers');
    Route::get('/customers/create', [App\Http\Controllers\Admin\CustomersController::class, 'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\Admin\CustomersController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [App\Http\Controllers\Admin\CustomersController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [App\Http\Controllers\Admin\CustomersController::class, 'update'])->name('customers.update');
    Route::get('/customers/{customer}/ledger', [App\Http\Controllers\Admin\CustomersController::class, 'ledger'])->name('customers.ledger');
    Route::get('/suppliers', [App\Http\Controllers\Admin\SuppliersController::class, 'index'])->name('suppliers');
    Route::get('/suppliers/create', [App\Http\Controllers\Admin\SuppliersController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [App\Http\Controllers\Admin\SuppliersController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [App\Http\Controllers\Admin\SuppliersController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [App\Http\Controllers\Admin\SuppliersController::class, 'update'])->name('suppliers.update');
    Route::get('/suppliers/{supplier}/ledger', [App\Http\Controllers\Admin\SuppliersController::class, 'ledger'])->name('suppliers.ledger');
    Route::get('/suppliers/{supplier}/statement.csv', [App\Http\Controllers\Admin\SuppliersController::class, 'statementCsv'])->name('suppliers.statement.csv');
    Route::get('/imports/import-order', [App\Http\Controllers\Admin\ImportOrdersController::class, 'index'])->name('imports.import-order');
    Route::get('/imports/import-order/create', [App\Http\Controllers\Admin\ImportOrdersController::class, 'create'])->name('imports.import-order.create');
    Route::get('/imports/import-order/template.csv', [App\Http\Controllers\Admin\ImportOrdersController::class, 'downloadTemplate'])->name('imports.import-order.template');
    Route::post('/imports/import-order/preview', [App\Http\Controllers\Admin\ImportOrdersController::class, 'preview'])->name('imports.import-order.preview');
    Route::post('/imports/import-order/confirm', [App\Http\Controllers\Admin\ImportOrdersController::class, 'confirm'])->name('imports.import-order.confirm');
    Route::get('/imports/import-order/{importOrder}', [App\Http\Controllers\Admin\ImportOrdersController::class, 'show'])->name('imports.import-order.show');
    Route::get('/bundles', [App\Http\Controllers\Admin\PosBundlesController::class, 'index'])->name('bundles');
    Route::post('/pos/bundles', [App\Http\Controllers\Admin\PosBundlesController::class, 'store'])->name('pos.bundles.store');
    Route::put('/pos/bundles/{bundle}', [App\Http\Controllers\Admin\PosBundlesController::class, 'update'])->name('pos.bundles.update');
    Route::delete('/pos/bundles/{bundle}', [App\Http\Controllers\Admin\PosBundlesController::class, 'destroy'])->name('pos.bundles.destroy');
    Route::get('/pos/bundles/{bundle}/items', function (App\Models\PosBundle $bundle) {
        $bundle->load('products');
        return response()->json([
            'success' => true,
            'items' => $bundle->products->map(fn ($p) => [
                'product_id' => $p->id,
                'quantity' => (float) $p->pivot->quantity,
            ])->values(),
        ]);
    })->name('pos.bundles.items');

    // Reports (Reference)
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/pnl', [App\Http\Controllers\Admin\ReportsController::class, 'pnl'])->name('reports.pnl');
    Route::get('/reports/trial-balance', [App\Http\Controllers\Admin\ReportsController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/reports/balance-sheet', [App\Http\Controllers\Admin\ReportsController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/reports/ar-aging', [App\Http\Controllers\Admin\ReportsController::class, 'arAging'])->name('reports.ar-aging');
    Route::get('/reports/ap-aging', [App\Http\Controllers\Admin\ReportsController::class, 'apAging'])->name('reports.ap-aging');
    Route::get('/reports/stock-valuation', [App\Http\Controllers\Admin\ReportsController::class, 'stockValuation'])->name('reports.stock-valuation');
    Route::get('/reports/purchase-register', [App\Http\Controllers\Admin\ReportsController::class, 'purchaseRegister'])->name('reports.purchase-register');
    Route::get('/reports/payment-register', [App\Http\Controllers\Admin\ReportsController::class, 'paymentRegister'])->name('reports.payment-register');
    Route::get('/reports/stock-transfer-register', [App\Http\Controllers\Admin\ReportsController::class, 'stockTransferRegister'])->name('reports.stock-transfer-register');

    // Investors (Reference)
    Route::get('/investors', [App\Http\Controllers\Admin\InvestorsController::class, 'index'])->name('investors');
    Route::post('/investors', [App\Http\Controllers\Admin\InvestorsController::class, 'store'])->name('investors.store');
    Route::get('/investors/{investor}', [App\Http\Controllers\Admin\InvestorsController::class, 'show'])->name('investors.show');
    Route::get('/investors/{investor}/edit', [App\Http\Controllers\Admin\InvestorsController::class, 'edit'])->name('investors.edit');
    Route::put('/investors/{investor}', [App\Http\Controllers\Admin\InvestorsController::class, 'update'])->name('investors.update');
    Route::post('/investors/{investor}/toggle-status', [App\Http\Controllers\Admin\InvestorsController::class, 'toggleStatus'])->name('investors.toggle-status');
    Route::post('/investors/{investor}/transactions', [App\Http\Controllers\Admin\InvestorsController::class, 'transactionStore'])->name('investors.transactions.store');

    Route::get('/investors/hub', [App\Http\Controllers\Admin\InvestorsController::class, 'hub'])->name('investors.hub');
    Route::get('/investors/portfolio', [App\Http\Controllers\Admin\InvestorsController::class, 'portfolio'])->name('investors.portfolio');
    Route::get('/investors/reports', [App\Http\Controllers\Admin\InvestorsController::class, 'reports'])->name('investors.reports');
    Route::get('/investors/reports.csv', [App\Http\Controllers\Admin\InvestorsController::class, 'reportsCsv'])->name('investors.reports.csv');

    // CRM (Reference)
    Route::get('/crm/hub', [App\Http\Controllers\Admin\CrmController::class, 'hub'])->name('crm.hub');

    Route::get('/crm/inbox', [App\Http\Controllers\Admin\CrmController::class, 'inbox'])->name('crm.inbox');
    Route::post('/crm/inbox', [App\Http\Controllers\Admin\CrmController::class, 'inboxStore'])->name('crm.inbox.store');
    Route::post('/crm/inbox/{message}/close', [App\Http\Controllers\Admin\CrmController::class, 'inboxClose'])->name('crm.inbox.close');

    Route::get('/crm/automations', [App\Http\Controllers\Admin\CrmController::class, 'automations'])->name('crm.automations');
    Route::post('/crm/automations', [App\Http\Controllers\Admin\CrmController::class, 'automationsStore'])->name('crm.automations.store');
    Route::post('/crm/automations/{automation}/toggle', [App\Http\Controllers\Admin\CrmController::class, 'automationsToggle'])->name('crm.automations.toggle');

    Route::get('/crm/preorders', [App\Http\Controllers\Admin\CrmController::class, 'preorders'])->name('crm.preorders');
    Route::post('/crm/preorders', [App\Http\Controllers\Admin\CrmController::class, 'preordersStore'])->name('crm.preorders.store');
    Route::post('/crm/preorders/{preorder}/close', [App\Http\Controllers\Admin\CrmController::class, 'preordersClose'])->name('crm.preorders.close');

    Route::get('/crm/referrals', [App\Http\Controllers\Admin\CrmController::class, 'referrals'])->name('crm.referrals');
    Route::post('/crm/referrals', [App\Http\Controllers\Admin\CrmController::class, 'referralsStore'])->name('crm.referrals.store');
    Route::post('/crm/referrals/{referral}/status', [App\Http\Controllers\Admin\CrmController::class, 'referralsUpdateStatus'])->name('crm.referrals.status');

    Route::get('/crm/loyalty', [App\Http\Controllers\Admin\CrmController::class, 'loyalty'])->name('crm.loyalty');
    Route::post('/crm/loyalty/accounts', [App\Http\Controllers\Admin\CrmController::class, 'loyaltyCreateAccount'])->name('crm.loyalty.accounts');
    Route::post('/crm/loyalty/adjust', [App\Http\Controllers\Admin\CrmController::class, 'loyaltyAdjust'])->name('crm.loyalty.adjust');

    Route::get('/crm/feedback', [App\Http\Controllers\Admin\CrmController::class, 'feedback'])->name('crm.feedback');
    Route::post('/crm/feedback', [App\Http\Controllers\Admin\CrmController::class, 'feedbackStore'])->name('crm.feedback.store');
    Route::post('/crm/feedback/{entry}/resolve', [App\Http\Controllers\Admin\CrmController::class, 'feedbackResolve'])->name('crm.feedback.resolve');

    Route::get('/crm/upsell', [App\Http\Controllers\Admin\CrmController::class, 'upsell'])->name('crm.upsell');
    Route::post('/crm/upsell', [App\Http\Controllers\Admin\CrmController::class, 'upsellStore'])->name('crm.upsell.store');
    Route::post('/crm/upsell/{campaign}/toggle', [App\Http\Controllers\Admin\CrmController::class, 'upsellToggle'])->name('crm.upsell.toggle');

    Route::get('/crm/customers', [App\Http\Controllers\Admin\CrmController::class, 'customers'])->name('crm.customers');

    // HRM (Reference)
    Route::get('/hrm', [App\Http\Controllers\Admin\HrmController::class, 'employees'])->name('hrm');
    Route::get('/hrm/employees', [App\Http\Controllers\Admin\HrmController::class, 'employees'])->name('hrm.employees');
    Route::get('/hrm/assets', [App\Http\Controllers\Admin\HrmAssetsController::class, 'index'])->name('hrm.assets');
    Route::post('/hrm/assets', [App\Http\Controllers\Admin\HrmAssetsController::class, 'store'])->name('hrm.assets.store');
    Route::put('/hrm/assets/{asset}', [App\Http\Controllers\Admin\HrmAssetsController::class, 'update'])->name('hrm.assets.update');
    Route::delete('/hrm/assets/{asset}', [App\Http\Controllers\Admin\HrmAssetsController::class, 'destroy'])->name('hrm.assets.destroy');
    Route::get('/hrm/payroll', [App\Http\Controllers\Admin\HrmController::class, 'payroll'])->name('hrm.payroll');
    Route::get('/hrm/payslips', [App\Http\Controllers\Admin\HrmController::class, 'payslips'])->name('hrm.payslips');
    Route::get('/hrm/leave', [App\Http\Controllers\Admin\HrmLeaveController::class, 'index'])->name('hrm.leave');
    Route::post('/hrm/leave/types', [App\Http\Controllers\Admin\HrmLeaveController::class, 'saveType'])->name('hrm.leave.types.save');
    Route::post('/hrm/leave/requests', [App\Http\Controllers\Admin\HrmLeaveController::class, 'storeRequest'])->name('hrm.leave.requests.store');
    Route::post('/hrm/leave/requests/{leaveRequest}/decide', [App\Http\Controllers\Admin\HrmLeaveController::class, 'decide'])->name('hrm.leave.decide');
    Route::get('/hrm/attendance', [App\Http\Controllers\Admin\HrmController::class, 'attendance'])->name('hrm.attendance');
    Route::get('/hrm/performance', [App\Http\Controllers\Admin\HrmRestController::class, 'performance'])->name('hrm.performance');
    Route::post('/hrm/performance', [App\Http\Controllers\Admin\HrmRestController::class, 'performanceStore'])->name('hrm.performance.store');
    Route::get('/hrm/recruitment', [App\Http\Controllers\Admin\HrmRestController::class, 'recruitment'])->name('hrm.recruitment');
    Route::post('/hrm/recruitment', [App\Http\Controllers\Admin\HrmRestController::class, 'recruitmentStore'])->name('hrm.recruitment.store');
    Route::get('/hrm/events', [App\Http\Controllers\Admin\HrmRestController::class, 'events'])->name('hrm.events');
    Route::post('/hrm/events', [App\Http\Controllers\Admin\HrmRestController::class, 'eventsStore'])->name('hrm.events.store');
    Route::get('/hrm/settings', [App\Http\Controllers\Admin\HrmRestController::class, 'settings'])->name('hrm.settings');

    // Data Import & Settings (Reference)
    Route::get('/data-import', function() { return view('admin.data_import', ['title' => 'Data Import Hub']); })->name('data-import');
    Route::get('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'general')->name('settings.general');
    Route::post('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'general')->name('settings.update');

    Route::get('/settings/users', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'users')->name('settings.users');
    Route::post('/settings/users', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'users')->name('settings.users.update');

    Route::get('/settings/approvals', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'approvals')->name('settings.approvals');
    Route::post('/settings/approvals', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'approvals')->name('settings.approvals.update');

    Route::get('/settings/accounting', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'accounting')->name('settings.accounting');
    Route::post('/settings/accounting', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'accounting')->name('settings.accounting.update');

    Route::get('/settings/whatsapp', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'whatsapp')->name('settings.whatsapp');
    Route::post('/settings/whatsapp', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'whatsapp')->name('settings.whatsapp.update');

    Route::get('/settings/location', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'location')->name('settings.location');
    Route::post('/settings/location', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'location')->name('settings.location.update');

    Route::get('/settings/inventory', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'inventory')->name('settings.inventory');
    Route::post('/settings/inventory', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'inventory')->name('settings.inventory.update');

    Route::get('/settings/receipt-template', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'receipt-template')->name('settings.receipt-template');
    Route::post('/settings/receipt-template', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'receipt-template')->name('settings.receipt-template.update');

    Route::get('/settings/invoice-template', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'invoice-template')->name('settings.invoice-template');
    Route::post('/settings/invoice-template', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'invoice-template')->name('settings.invoice-template.update');

    Route::get('/settings/report-templates', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'report-templates')->name('settings.report-templates');
    Route::post('/settings/report-templates', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'report-templates')->name('settings.report-templates.update');

    Route::get('/settings/display', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'display')->name('settings.display');
    Route::post('/settings/display', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'display')->name('settings.display.update');

    Route::get('/settings/pricelist-template', [App\Http\Controllers\Admin\SettingsController::class, 'show'])->defaults('page', 'pricelist-template')->name('settings.pricelist-template');
    Route::post('/settings/pricelist-template', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->defaults('page', 'pricelist-template')->name('settings.pricelist-template.update');

    // Coming Soon (Reference)
    Route::get('/media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('/services', [App\Http\Controllers\Admin\ServicesController::class, 'index'])->name('services');
    Route::post('/services', [App\Http\Controllers\Admin\ServicesController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [App\Http\Controllers\Admin\ServicesController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [App\Http\Controllers\Admin\ServicesController::class, 'destroy'])->name('services.destroy');
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
    Route::get('/konnect', function() { return view('admin.konnect_hub', ['title' => 'Konnect Hub']); })->name('konnect');

    // ELMS
    Route::get('/elms/courses', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'index'])->name('elms.courses');
    Route::get('/elms/courses/create', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'create'])->name('elms.courses.create');
    Route::post('/elms/courses', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'store'])->name('elms.courses.store');
    Route::get('/elms/courses/{course}', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'show'])->name('elms.courses.show');
    Route::get('/elms/courses/{course}/edit', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'edit'])->name('elms.courses.edit');
    Route::put('/elms/courses/{course}', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'update'])->name('elms.courses.update');
    Route::post('/elms/courses/{course}/toggle-status', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'toggleStatus'])->name('elms.courses.toggle-status');

    Route::post('/elms/courses/{course}/fees', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'feeStore'])->name('elms.courses.fees.store');
    Route::put('/elms/courses/{course}/fees/{fee}', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'feeUpdate'])->name('elms.courses.fees.update');
    Route::post('/elms/courses/{course}/fees/{fee}/toggle', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'feeToggle'])->name('elms.courses.fees.toggle');
    Route::delete('/elms/courses/{course}/fees/{fee}', [App\Http\Controllers\Admin\ElmsCoursesController::class, 'feeDelete'])->name('elms.courses.fees.delete');

    Route::get('/elms/categories', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'index'])->name('elms.categories');
    Route::get('/elms/categories/create', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'create'])->name('elms.categories.create');
    Route::post('/elms/categories', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'store'])->name('elms.categories.store');
    Route::get('/elms/categories/{category}/edit', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'edit'])->name('elms.categories.edit');
    Route::put('/elms/categories/{category}', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'update'])->name('elms.categories.update');
    Route::post('/elms/categories/{category}/toggle-status', [App\Http\Controllers\Admin\ElmsArticleCategoriesController::class, 'toggleStatus'])->name('elms.categories.toggle-status');

    Route::get('/elms/articles', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'index'])->name('elms.articles');
    Route::get('/elms/articles/create', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'create'])->name('elms.articles.create');
    Route::post('/elms/articles', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'store'])->name('elms.articles.store');
    Route::get('/elms/articles/{article}/edit', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'edit'])->name('elms.articles.edit');
    Route::put('/elms/articles/{article}', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'update'])->name('elms.articles.update');
    Route::delete('/elms/articles/{article}', [App\Http\Controllers\Admin\ElmsArticlesController::class, 'destroy'])->name('elms.articles.destroy');

    Route::get('/elms/trainers', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'index'])->name('elms.trainers.index');
    Route::get('/elms/trainers/create', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'create'])->name('elms.trainers.create');
    Route::post('/elms/trainers', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'store'])->name('elms.trainers.store');
    Route::get('/elms/trainers/{trainer}/edit', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'edit'])->name('elms.trainers.edit');
    Route::put('/elms/trainers/{trainer}', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'update'])->name('elms.trainers.update');
    Route::post('/elms/trainers/{trainer}/toggle-status', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'toggleStatus'])->name('elms.trainers.toggle-status');
    Route::delete('/elms/trainers/{trainer}', [App\Http\Controllers\Admin\ElmsTrainersController::class, 'destroy'])->name('elms.trainers.destroy');

    // ERP Modules
    Route::get('/inventory', function() { return view('admin.erp.inventory'); })->name('inventory');
    Route::get('/inventory/stock-levels', function() { return view('admin.erp.inventory_stock_levels'); })->name('inventory.stock-levels');
    Route::get('/inventory/products', [App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('inventory.products');
    Route::get('/inventory/products/create', [App\Http\Controllers\Admin\ProductsController::class, 'create'])->name('inventory.products.create');
    Route::get('/inventory/products/import', [App\Http\Controllers\Admin\ProductsController::class, 'import'])->name('inventory.products.import');
    Route::get('/inventory/products/import/template.csv', [App\Http\Controllers\Admin\ProductsController::class, 'downloadTemplate'])->name('inventory.products.import.template');
    Route::post('/inventory/products/import/preview', [App\Http\Controllers\Admin\ProductsController::class, 'importPreview'])->name('inventory.products.import.preview');
    Route::post('/inventory/products/import/confirm', [App\Http\Controllers\Admin\ProductsController::class, 'importConfirm'])->name('inventory.products.import.confirm');
    Route::post('/inventory/products/upload-image', [App\Http\Controllers\Admin\ProductsController::class, 'uploadImage'])->name('inventory.products.upload-image');
    Route::post('/inventory/products', [App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('inventory.products.store');
    Route::get('/inventory/products/{product}/edit', [App\Http\Controllers\Admin\ProductsController::class, 'edit'])->name('inventory.products.edit');
    Route::put('/inventory/products/{product}', [App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('inventory.products.update');
    Route::delete('/inventory/products/{product}', [App\Http\Controllers\Admin\ProductsController::class, 'destroy'])->name('inventory.products.destroy');
    Route::get('/inventory/suppliers', function() { return view('admin.erp.inventory_suppliers'); })->name('inventory.suppliers');

    Route::get('/sales', [App\Http\Controllers\Admin\SalesFinanceController::class, 'dashboard'])->name('sales');
    Route::get('/sales/invoices', [App\Http\Controllers\Admin\SalesModuleController::class, 'invoices'])->name('sales.invoices');
    Route::get('/sales/payments', [App\Http\Controllers\Admin\SalesModuleController::class, 'payments'])->name('sales.payments');
    Route::get('/sales/payments.csv', [App\Http\Controllers\Admin\SalesModuleController::class, 'paymentsCsv'])->name('sales.payments.csv');

    Route::get('/expenses', [App\Http\Controllers\Admin\SalesFinanceController::class, 'expenses'])->name('expenses');
    
    // HRM Modules
    Route::get('/employees', [App\Http\Controllers\Admin\HrmController::class, 'employees'])->name('employees');
    Route::post('/employees', [App\Http\Controllers\Admin\HrmController::class, 'employeeStore'])->name('hrm.employee.store');
    Route::get('/employees/{employee}', [App\Http\Controllers\Admin\HrmController::class, 'employeeShow'])->name('hrm.employee.show');

    Route::get('/payroll', [App\Http\Controllers\Admin\HrmController::class, 'payroll'])->name('payroll');
    Route::post('/payroll/components', [App\Http\Controllers\Admin\HrmController::class, 'payrollComponentStore'])->name('hrm.payroll.components.store');
    Route::post('/payroll/components/{component}/toggle', [App\Http\Controllers\Admin\HrmController::class, 'payrollComponentToggle'])->name('hrm.payroll.components.toggle');
    Route::post('/payroll/run', [App\Http\Controllers\Admin\HrmController::class, 'payrollRun'])->name('hrm.payroll.run');
    Route::get('/payroll/payslips/{payslip}', [App\Http\Controllers\Admin\HrmController::class, 'payslipShow'])->name('hrm.payslip.show');

    Route::get('/attendance', [App\Http\Controllers\Admin\HrmController::class, 'attendance'])->name('attendance');
    Route::post('/attendance', [App\Http\Controllers\Admin\HrmController::class, 'attendanceStore'])->name('hrm.attendance.store');
    
    // POS & Messaging
    Route::get('/pos', [App\Http\Controllers\Admin\PosController::class, 'index'])->name('pos');
    Route::get('/pos/orders', [App\Http\Controllers\Admin\PosController::class, 'orders'])->name('pos.orders');
    Route::post('/pos/order', [App\Http\Controllers\Admin\PosController::class, 'storeOrder'])->name('pos.store');
    Route::get('/pos/order/{order}/receipt', [App\Http\Controllers\Admin\PosController::class, 'showReceipt'])->name('pos.receipt');
    Route::get('/messaging', function() { return view('admin.messaging.index'); })->name('messaging');
    
    // Mothers & Content (Existing logic should be added here later)
    Route::get('/mothers', [App\Http\Controllers\Admin\MothersController::class, 'index'])->name('mothers');
    Route::post('/mothers', [App\Http\Controllers\Admin\MothersController::class, 'store'])->name('mothers.store');
    Route::post('/mothers/{mother}/approve', [App\Http\Controllers\Admin\MothersController::class, 'approve'])->name('mothers.approve');
    Route::get('/mothers/{mother}', [App\Http\Controllers\Admin\MothersController::class, 'show'])->name('mothers.show');
    Route::get('/mothers/import', [App\Http\Controllers\Admin\MothersController::class, 'import'])->name('mothers.import');
    Route::post('/mothers/import/preview', [App\Http\Controllers\Admin\MothersController::class, 'importPreview'])->name('mothers.import.preview');
    Route::post('/mothers/import/confirm', [App\Http\Controllers\Admin\MothersController::class, 'importConfirm'])->name('mothers.import.confirm');
    Route::get('/mothers/{mother}/edit', [App\Http\Controllers\Admin\MothersController::class, 'edit'])->name('mothers.edit');
    Route::put('/mothers/{mother}', [App\Http\Controllers\Admin\MothersController::class, 'update'])->name('mothers.update');
    Route::delete('/mothers/{mother}', [App\Http\Controllers\Admin\MothersController::class, 'destroy'])->name('mothers.destroy');
    Route::get('/mothers/{mother}/messages', [App\Http\Controllers\Admin\MothersController::class, 'messages'])->name('mothers.messages');
    Route::post('/mothers/{mother}/messages/send', [App\Http\Controllers\Admin\MothersController::class, 'sendMessage'])->name('mothers.messages.send');
    Route::get('/articles', function() { return view('admin.articles.index'); })->name('articles');
});

Route::get('/products', [App\Http\Controllers\ProductSelectionController::class, 'index'])->name('products.index');
Route::post('/products/submit', [App\Http\Controllers\ProductSelectionController::class, 'submit'])->name('products.submit');
Route::get('/orders/status/{token}', [App\Http\Controllers\ProductSelectionController::class, 'status'])->name('orders.status');
Route::post('/orders/pay/{token}', [App\Http\Controllers\ProductSelectionController::class, 'processPayment'])->name('orders.pay');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
