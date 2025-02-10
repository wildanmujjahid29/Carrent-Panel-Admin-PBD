<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mobil extends Model
{
    protected $table = 'mobils';
    
    protected $guarded = [];


    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }


    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
