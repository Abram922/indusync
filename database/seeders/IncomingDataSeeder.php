<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomingInventory; 
use App\Models\Inventory; // Import model Inventory
use Carbon\Carbon; // Untuk manipulasi tanggal

class IncomingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pastikan ada data di tabel inventories terlebih dahulu
        $inventoryIds = Inventory::pluck('id')->toArray();

        if (empty($inventoryIds)) {
            $this->command->info('Tidak ada data di tabel inventories. Jalankan InventorySeeder terlebih dahulu.');
            return;
        }

        // Data dummy stok masuk
        $incomingData = [
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 10,
                'supplier' => 'Supplier A',
                'received_date' => Carbon::now()->subDays(10),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 15,
                'supplier' => 'Supplier B',
                'received_date' => Carbon::now()->subDays(8),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 20,
                'supplier' => 'Supplier C',
                'received_date' => Carbon::now()->subDays(5),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 25,
                'supplier' => 'Supplier D',
                'received_date' => Carbon::now()->subDays(3),
            ],
            
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 30,
                'supplier' => 'Supplier E',
                'received_date' => Carbon::now(),
            ],
        ];

        // Simpan data ke tabel incoming_data
        foreach ($incomingData as $data) {
            IncomingInventory::create($data);
        }
    }
}
