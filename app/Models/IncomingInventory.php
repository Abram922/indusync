<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingInventory extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan default plural
    protected $table = 'incoming_inventories'; // Pastikan nama tabel sesuai dengan yang ada di database

    protected $fillable = ['inventory_id', 'quantity', 'supplier', 'received_date'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
