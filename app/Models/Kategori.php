<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';
    
    protected $guarded = [];

    public function mobil(): HasMany
    {
        return $this->hasMany(Mobil::class, 'kategori_id', 'id');
    }
}
