<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'platform_name', 'value' => 'CareMate BD', 'type' => 'string', 'group' => 'general'],
            ['key' => 'platform_tagline', 'value' => 'Care that feels like family, found in minutes.', 'type' => 'string', 'group' => 'general'],
            ['key' => 'platform_commission_rate', 'value' => '15.0', 'type' => 'float', 'group' => 'financial'],
            ['key' => 'contact_email', 'value' => 'contact@carematebd.com', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+880 1610-296460', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'emergency_hotline', 'value' => '+880 1610-296460', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'office_address', 'value' => 'E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'currency', 'value' => 'BDT', 'type' => 'string', 'group' => 'financial'],
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'string', 'group' => 'financial'],
            ['key' => 'cancellation_fee_hours', 'value' => '24', 'type' => 'integer', 'group' => 'booking'],
            ['key' => 'min_payout_amount', 'value' => '1000', 'type' => 'integer', 'group' => 'financial'],
            ['key' => 'verification_required_documents', 'value' => json_encode(['nid_front', 'nid_back', 'caregiving_certificate']), 'type' => 'json', 'group' => 'verification'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
