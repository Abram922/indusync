<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data dummy inventaris tambahan
        $inventories = [
            [
                'name' => 'Keyboard',
                'description' => 'Keyboard mekanik untuk kebutuhan mengetik',
            ],
            [
                'name' => 'Mouse Gaming',
                'description' => 'Mouse ergonomis dan responsif untuk kebutuhan gaming dan kantor',
            ],
            [
                'name' => 'Monitor 24 inch',
                'description' => 'Monitor LED 24 inch untuk meningkatkan produktivitas',
            ],
            [
                'name' => 'Scanner',
                'description' => 'Scanner untuk kebutuhan digitalisasi dokumen',
            ],
            [
                'name' => 'Speaker Bluetooth',
                'description' => 'Speaker Bluetooth untuk mendukung rapat dan presentasi',
            ],
            [
                'name' => 'Earphone In-Ear',
                'description' => 'Earphone untuk kebutuhan pribadi saat bekerja',
            ],
            [
                'name' => 'Hard Disk External',
                'description' => 'Hard disk external untuk penyimpanan data tambahan',
            ],
            [
                'name' => 'USB Hub',
                'description' => 'USB hub untuk memperluas port USB pada komputer',
            ],
            [
                'name' => 'Charger Multiport',
                'description' => 'Charger multiport untuk mengisi daya perangkat banyak secara bersamaan',
            ],
            [
                'name' => 'Docking Station',
                'description' => 'Docking station untuk menghubungkan perangkat periferal ke laptop',
            ],
            [
                'name' => 'Microphone USB',
                'description' => 'Microphone USB untuk kebutuhan rekaman atau rapat online',
            ],
            [
                'name' => 'Tripod Kamera',
                'description' => 'Tripod kamera untuk kebutuhan perekaman video atau foto',
            ],
            [
                'name' => 'Kabel Ethernet',
                'description' => 'Kabel Ethernet untuk koneksi jaringan yang stabil',
            ],
            [
                'name' => 'Headphone Over-Ear',
                'description' => 'Headphone over-ear untuk kenyamanan saat mendengarkan musik atau rapat',
            ],
            [
                'name' => 'Mousepad Gaming',
                'description' => 'Mousepad besar untuk kenyamanan bermain game atau bekerja',
            ],
            [
                'name' => 'Power Bank',
                'description' => 'Power bank untuk pengisian daya perangkat saat bepergian',
            ],
            [
                'name' => 'Cooling Pad Laptop',
                'description' => 'Cooling pad untuk menjaga laptop tetap dingin saat digunakan lama',
            ],
            [
                'name' => 'Cable Management Kit',
                'description' => 'Kit manajemen kabel untuk menjaga meja tetap rapi',
            ],
            [
                'name' => 'Lampu Meja LED',
                'description' => 'Lampu meja LED untuk pencahayaan optimal saat bekerja',
            ],
            [
                'name' => 'Adjustable Stand Laptop',
                'description' => 'Stand laptop adjustable untuk postur kerja yang nyaman',
            ],
        ];

        // Simpan data ke tabel inventories
        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
