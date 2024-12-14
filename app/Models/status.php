<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class status extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
