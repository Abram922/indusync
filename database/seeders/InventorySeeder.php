<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Data dummy inventaris
        $inventories = [
            [
                'name' => 'Laptop',
                'description' => 'Laptop untuk kebutuhan kantor',
            ],
            [
                'name' => 'Meja Kantor',
                'description' => 'Meja kerja untuk staff administrasi',
            ],
            [
                'name' => 'Printer',
                'description' => 'Printer untuk kebutuhan cetak dokumen',
            ],
            [
                'name' => 'Kursi Ergonomis',
                'description' => 'Kursi ergonomis untuk kenyamanan kerja',
            ],
            [
                'name' => 'Proyektor',
                'description' => 'Proyektor untuk presentasi dan meeting',
            ],
        ];

        // Simpan data ke tabel inventories
        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }

}