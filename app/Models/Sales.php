<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inventory_id',
        'quantity',
        'namaCustomer',
        'tanggalPenjualan',
        'harga',
        'Keterangan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggalPenjualan' => 'date',
        'harga' => 'float',
    ];

    /**
     * Define the relationship with the Inventory model.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
