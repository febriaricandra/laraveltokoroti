<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Specialities'],
            ['name' => 'Donuts'],
            ['name' => 'Beverage'],
            ['name' => 'Cookies'],
            ['name' => 'Other'],
        ];

        DB::table('categories')->insert($categories);
    }
}
