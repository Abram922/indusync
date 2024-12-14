<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    // Kolom yang dapat diisi
    protected $fillable = [
        'inventory_id',
        'quantity',
        'supllier',
        'tanggalPembelian',
        'tanggalPengiriman',
        'resi',
        'status_id',
    ];

    // Relasi ke tabel `inventories`
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relasi ke tabel `statuses`
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
