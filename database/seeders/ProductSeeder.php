<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori
        $categories = DB::table('categories')->pluck('id', 'name');

        $products = [
            [
                'name' => 'Chocolate Donut',
                'category_id' => $categories['Donuts'],
                'description' => 'Donat dengan lapisan coklat yang lezat.',
                'price' => 15000,
                'image' => null,
            ],
            [
                'name' => 'Latte Coffee',
                'category_id' => $categories['Beverage'],
                'description' => 'Kopi latte dengan rasa creamy.',
                'price' => 25000,
                'image' => null,
            ],
            [
                'name' => 'Butter Cookies',
                'category_id' => $categories['Cookies'],
                'description' => 'Kue kering mentega dengan rasa lembut.',
                'price' => 30000,
                'image' => null,
            ],
            [
                'name' => 'Cheese Special Bun',
                'category_id' => $categories['Specialities'],
                'description' => 'Roti spesial dengan keju yang meleleh.',
                'price' => 20000,
                'image' => null,
            ],
            [
                'name' => 'Snack Box',
                'category_id' => $categories['Other'],
                'description' => 'Paket snack berisi berbagai pilihan roti dan kue.',
                'price' => 50000,
                'image' => null,
            ],
        ];

        DB::table('products')->insert($products);
    }
}
