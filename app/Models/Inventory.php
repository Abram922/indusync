<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventories';

    protected $fillable = [
        'name', 'description'
    ];

    public function incomingInventories()
    {
        return $this->hasMany(IncomingInventory::class, 'inventory_id');
    }

    public function outcomingInventories()
    {
        return $this->hasMany(OutcomingInventory::class, 'inventory_id');
    }

    // Getter untuk menghitung stok
    public function getStockAttribute()
    {
        $incomingTotal = $this->incomingInventories()->sum('quantity');
        $outcomingTotal = $this->outcomingInventories()->sum('quantity');

        return $incomingTotal - $outcomingTotal;
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}

