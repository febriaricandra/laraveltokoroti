<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'shop_name', 'value' => 'Toko Roti Dinar', 'type' => 'text', 'group' => 'general', 'description' => 'Nama toko'],
            ['key' => 'shop_address', 'value' => 'Jl. Contoh No. 123, Jakarta', 'type' => 'text', 'group' => 'general', 'description' => 'Alamat toko'],
            ['key' => 'shop_phone', 'value' => '021-1234567', 'type' => 'text', 'group' => 'general', 'description' => 'Nomor telepon toko'],
            ['key' => 'shop_email', 'value' => 'info@tokorotidinar.com', 'type' => 'text', 'group' => 'general', 'description' => 'Email toko'],
            
            // Shipping Settings
            ['key' => 'shop_postal_code', 'value' => '12345', 'type' => 'text', 'group' => 'shipping', 'description' => 'Kode pos toko'],
            ['key' => 'shop_province_id', 'value' => '6', 'type' => 'number', 'group' => 'shipping', 'description' => 'ID Provinsi toko (DKI Jakarta)'],
            ['key' => 'shop_city_id', 'value' => '152', 'type' => 'number', 'group' => 'shipping', 'description' => 'ID Kota toko (Jakarta Pusat)'],
            ['key' => 'enable_shipping_cost', 'value' => '1', 'type' => 'boolean', 'group' => 'shipping', 'description' => 'Aktifkan perhitungan ongkos kirim'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], 
                $setting
            );
        }
    }
}
