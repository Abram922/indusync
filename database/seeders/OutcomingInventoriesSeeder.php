<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OutcomingInventoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('outcoming_inventories')->insert([
            [
                'inventory_id' => 1, // Pastikan ID ini sesuai dengan data di tabel inventories
                'quantity' => 50,
                'receiver' => 'John Doe',
                'issued_date' => Carbon::now()->subDays(3)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'inventory_id' => 2, // Pastikan ID ini sesuai dengan data di tabel inventories
                'quantity' => 30,
                'receiver' => 'Jane Smith',
                'issued_date' => Carbon::now()->subDays(7)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'inventory_id' => 3, // Pastikan ID ini sesuai dengan data di tabel inventories
                'quantity' => 20,
                'receiver' => 'Mike Johnson',
                'issued_date' => Carbon::now()->subDays(1)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
