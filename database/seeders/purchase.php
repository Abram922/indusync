<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class purchase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Generate 15 entries
        foreach (range(1, 15) as $index) {
            DB::table('purchases')->insert([
                'inventory_id' => rand(11, 20), // ID dari tabel inventories (inventory_id from 11 to 20)
                'quantity' => $faker->numberBetween(1, 100),
                'supllier' => $faker->company,  // Random supplier name
                'tanggalPembelian' => $faker->date(),
                'tanggalPengiriman' => $faker->date(),
                'resi' => $faker->unique()->numberBetween(1000, 9999),  // Unique resi
                'status_id' => rand(1, 2), // ID dari tabel statuses
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

