<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class ItemOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'jumlah_keluar',
        'tanggal_keluar',
        'penerima',
        'user_id',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

