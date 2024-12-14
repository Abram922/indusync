<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomingInventory extends Model
{
    use HasFactory;
    protected $table = 'outcoming_inventories';

    protected $fillable = ['inventory_id', 'quantity', 'receiver', 'issued_date','keterangan','harga','customer_name'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
