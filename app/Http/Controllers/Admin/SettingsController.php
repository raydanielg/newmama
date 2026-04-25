<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private function pages(): array
    {
        return [
            'general' => [
                'title' => 'Settings - General',
                'fields' => [
                    ['key' => 'site.name', 'label' => 'Site Name (Display)', 'type' => 'text'],
                    ['key' => 'company.name', 'label' => 'Legal Company Name', 'type' => 'text'],
                    ['key' => 'company.phone', 'label' => 'Company Phone', 'type' => 'text'],
                    ['key' => 'company.email', 'label' => 'Company Email', 'type' => 'email'],
                    ['key' => 'company.address', 'label' => 'Company Address', 'type' => 'text'],
                    ['key' => 'site.logo_url', 'label' => 'Logo URL (Public)', 'type' => 'text'],
                    ['key' => 'site.favicon_url', 'label' => 'Favicon URL', 'type' => 'text'],
                    ['key' => 'timezone', 'label' => 'Timezone', 'type' => 'text'],
                ],
            ],
            'users' => [
                'title' => 'Settings - Users',
                'fields' => [
                    ['key' => 'users.default_role', 'label' => 'Default Role', 'type' => 'text'],
                    ['key' => 'users.require_2fa', 'label' => 'Require 2FA (true/false)', 'type' => 'text'],
                ],
            ],
            'approvals' => [
                'title' => 'Settings - Approvals',
                'fields' => [
                    ['key' => 'approvals.vouchers', 'label' => 'Vouchers Require Approval (true/false)', 'type' => 'text'],
                    ['key' => 'approvals.payroll', 'label' => 'Payroll Require Approval (true/false)', 'type' => 'text'],
                ],
            ],
            'accounting' => [
                'title' => 'Settings - Accounting',
                'fields' => [
                    ['key' => 'accounting.currency', 'label' => 'Currency Code', 'type' => 'text'],
                    ['key' => 'accounting.fiscal_year_start', 'label' => 'Fiscal Year Start (MM-DD)', 'type' => 'text'],
                ],
            ],
            'whatsapp' => [
                'title' => 'Settings - WhatsApp',
                'fields' => [
                    ['key' => 'whatsapp.enabled', 'label' => 'Enabled (true/false)', 'type' => 'text'],
                    ['key' => 'whatsapp.provider', 'label' => 'Provider', 'type' => 'text'],
                    ['key' => 'whatsapp.api_url', 'label' => 'API Endpoint URL', 'type' => 'text'],
                    ['key' => 'whatsapp.api_key', 'label' => 'API Key / Token', 'type' => 'text'],
                    ['key' => 'whatsapp.from_number', 'label' => 'From Number', 'type' => 'text'],
                    ['key' => 'whatsapp.webhook_url', 'label' => 'Our Webhook URL (Public)', 'type' => 'text'],
                    ['key' => 'whatsapp.webhook_secret', 'label' => 'Webhook Secret', 'type' => 'text'],
                ],
            ],
            'location' => [
                'title' => 'Settings - Location',
                'fields' => [
                    ['key' => 'location.country', 'label' => 'Country', 'type' => 'text'],
                    ['key' => 'location.city', 'label' => 'City', 'type' => 'text'],
                ],
            ],
            'inventory' => [
                'title' => 'Settings - Inventory',
                'fields' => [
                    ['key' => 'inventory.negative_stock', 'label' => 'Allow Negative Stock (true/false)', 'type' => 'text'],
                    ['key' => 'inventory.default_location', 'label' => 'Default Location', 'type' => 'text'],
                ],
            ],
            'receipt-template' => [
                'title' => 'Settings - Receipt Template',
                'fields' => [
                    ['key' => 'receipt.header', 'label' => 'Header Text', 'type' => 'text'],
                    ['key' => 'receipt.footer', 'label' => 'Footer Text', 'type' => 'text'],
                ],
            ],
            'invoice-template' => [
                'title' => 'Settings - Invoice Template',
                'fields' => [
                    ['key' => 'invoice.header', 'label' => 'Header Text', 'type' => 'text'],
                    ['key' => 'invoice.footer', 'label' => 'Footer Text', 'type' => 'text'],
                ],
            ],
            'report-templates' => [
                'title' => 'Settings - Report Templates',
                'fields' => [
                    ['key' => 'reports.branding', 'label' => 'Branding (true/false)', 'type' => 'text'],
                ],
            ],
            'display' => [
                'title' => 'Settings - Display',
                'fields' => [
                    ['key' => 'ui.theme', 'label' => 'Theme', 'type' => 'text'],
                    ['key' => 'ui.density', 'label' => 'Table Density', 'type' => 'text'],
                ],
            ],
            'integrations' => [
                'title' => 'Settings - Integrations',
                'fields' => [
                    ['key' => 'integrations.snippesh_key', 'label' => 'Snippe.sh API Key', 'type' => 'text'],
                    ['key' => 'integrations.snippesh_url', 'label' => 'Snippe.sh Endpoint', 'type' => 'text'],
                ],
            ],
            'pricelist-template' => [
                'title' => 'Settings - Pricelist Template',
                'fields' => [
                    ['key' => 'pricelist.header', 'label' => 'Header Text', 'type' => 'text'],
                    ['key' => 'pricelist.footer', 'label' => 'Footer Text', 'type' => 'text'],
                ],
            ],
        ];
    }

    private function resolvePage(string $page): array
    {
        $pages = $this->pages();
        abort_unless(isset($pages[$page]), 404);
        return $pages[$page];
    }

    public function show(Request $request, string $page)
    {
        $cfg = $this->resolvePage($page);
        $keys = array_map(fn ($f) => $f['key'], $cfg['fields']);

        $values = SystemSetting::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.settings.page', [
            'title' => $cfg['title'],
            'page' => $page,
            'fields' => $cfg['fields'],
            'values' => $values,
        ]);
    }

    public function update(Request $request, string $page)
    {
        $cfg = $this->resolvePage($page);

        $rules = [];
        foreach ($cfg['fields'] as $f) {
            $rules[$f['key']] = ['nullable', 'string'];
        }

        $data = $request->validate($rules);

        foreach ($cfg['fields'] as $f) {
            $k = $f['key'];
            SystemSetting::updateOrCreate(['key' => $k], ['value' => $data[$k] ?? null]);
        }

        return redirect()->back()->with('status', 'Settings saved');
    }
}
