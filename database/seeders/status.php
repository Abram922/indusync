<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class status extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
        [
            'status' => 'Sedang Diproses',

        ],
        [
            'status' => 'Selesai'
        ]
    
    ]);
  
    }
}