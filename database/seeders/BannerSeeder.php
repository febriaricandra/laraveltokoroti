<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{

    public function run(): void
    {

        $banners = [
            [
                'image' => null,
                'description' => 'Selamat datang di toko roti kami, nikmati berbagai pilihan roti lezat kami!',
            ],
            [
                'image' => null,
                'description' => 'Temukan beragam pilihan roti favorit Anda di sini! Mulai dari roti tawar lembut, roti manis aneka rasa, hingga roti gandum sehat. Semua dipanggang dengan cinta untuk keluarga Anda.',
            ],
            [
                'image' => null,
                'description' => 'Dapatkan diskon spesial untuk pembelian roti hari ini! Untuk potongan harga 10% pada semua produk roti kami. Jangan lewatkan kesempatan ini!',
            ],
        ];

        // Masukkan data ke dalam tabel banners
        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
