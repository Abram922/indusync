<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        // Menambahkan beberapa data dummy untuk tabel 'inventories'
        DB::table('inventories')->insert([
            [
                'kode_barang' => '1001',
                'namabarang' => 'Laptop',
                'quantity' => 50,
                'tanggal_masuk' => Carbon::now(),
                'tanggal_input' => Carbon::now(),
                'jenis' => 'Elektronik',
                'keterangan' => 'Laptop dengan spek tinggi untuk kebutuhan kantor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_barang' => '1002',
                'namabarang' => 'Smartphone',
                'quantity' => 100,
                'tanggal_masuk' => Carbon::now(),
                'tanggal_input' => Carbon::now(),
                'jenis' => 'Elektronik',
                'keterangan' => 'Smartphone terbaru dengan kamera 48MP',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_barang' => '1003',
                'namabarang' => 'Meja Kantor',
                'quantity' => 20,
                'tanggal_masuk' => Carbon::now(),
                'tanggal_input' => Carbon::now(),
                'jenis' => 'Perabot',
                'keterangan' => 'Meja kantor dengan desain minimalis',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_barang' => '1004',
                'namabarang' => 'Kursi Kantor',
                'quantity' => 30,
                'tanggal_masuk' => Carbon::now(),
                'tanggal_input' => Carbon::now(),
                'jenis' => 'Perabot',
                'keterangan' => 'Kursi kantor ergonomis untuk kenyamanan kerja',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kode_barang' => '1005',
                'namabarang' => 'Proyektor',
                'quantity' => 10,
                'tanggal_masuk' => Carbon::now(),
                'tanggal_input' => Carbon::now(),
                'jenis' => 'Elektronik',
                'keterangan' => 'Proyektor untuk presentasi dengan resolusi tinggi',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}