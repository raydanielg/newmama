<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class PaymentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'whatsapp_number',
                'value' => '255742710054',
            ],
            [
                'key' => 'snippe_public_key',
                'value' => 'pk_live_xxxx',
            ],
            [
                'key' => 'snippe_secret_key',
                'value' => 'sk_live_xxxx',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
