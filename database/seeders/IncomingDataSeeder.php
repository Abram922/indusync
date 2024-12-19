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

        // Data dummy stok masuk tambahan
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
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 12,
                'supplier' => 'Supplier F',
                'received_date' => Carbon::now()->subDays(15),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 18,
                'supplier' => 'Supplier G',
                'received_date' => Carbon::now()->subDays(7),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 22,
                'supplier' => 'Supplier H',
                'received_date' => Carbon::now()->subDays(6),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 28,
                'supplier' => 'Supplier I',
                'received_date' => Carbon::now()->subDays(4),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 35,
                'supplier' => 'Supplier J',
                'received_date' => Carbon::now()->subDays(2),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 14,
                'supplier' => 'Supplier K',
                'received_date' => Carbon::now()->subDays(12),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 21,
                'supplier' => 'Supplier L',
                'received_date' => Carbon::now()->subDays(9),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 19,
                'supplier' => 'Supplier M',
                'received_date' => Carbon::now()->subDays(11),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 24,
                'supplier' => 'Supplier N',
                'received_date' => Carbon::now()->subDays(8),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 33,
                'supplier' => 'Supplier O',
                'received_date' => Carbon::now()->subDays(1),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 17,
                'supplier' => 'Supplier P',
                'received_date' => Carbon::now()->subDays(14),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 26,
                'supplier' => 'Supplier Q',
                'received_date' => Carbon::now()->subDays(5),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 32,
                'supplier' => 'Supplier R',
                'received_date' => Carbon::now()->subDays(3),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 27,
                'supplier' => 'Supplier S',
                'received_date' => Carbon::now()->subDays(4),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 29,
                'supplier' => 'Supplier T',
                'received_date' => Carbon::now()->subDays(6),
            ],
            [
                'inventory_id' => $inventoryIds[array_rand($inventoryIds)],
                'quantity' => 31,
                'supplier' => 'Supplier U',
                'received_date' => Carbon::now()->subDays(2),
            ],
        ];

        // Simpan data ke tabel incoming_inventories
        foreach ($incomingData as $data) {
            IncomingInventory::create($data);
        }
    }
}
