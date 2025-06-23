<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount; // Import model Discount

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Discount::query()->delete();

        Discount::create([
            'name' => 'Diskon Lebaran',
            'minimum_order' => 50000,
            'discount_percentage' => 0.1,
            'is_active' => true,
        ]);

        Discount::create([
            'name' => 'Promo Gajian',
            'minimum_order' => 150000,
            'discount_percentage' => 0.15,
            'is_active' => true,
        ]);

        Discount::create([
            'name' => 'Diskon Akhir Pekan',
            'minimum_order' => 75000,
            'discount_percentage' => 0.5,
            'is_active' => true,
        ]);

        Discount::create([
            'name' => 'Flash Sale Tengah Malam',
            'minimum_order' => 200000,
            'discount_percentage' => 0.25,
            'is_active' => false,
        ]);
    }
}
