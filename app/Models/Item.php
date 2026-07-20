<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok_minimal',
        'stok_sekarang',
    ];

    public function itemIns(): HasMany
    {
        return $this->hasMany(ItemIn::class, 'item_id');
    }

    public function itemOuts(): HasMany
    {
        return $this->hasMany(ItemOut::class, 'item_id');
    }
}

