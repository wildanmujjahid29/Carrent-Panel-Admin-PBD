<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';

    protected $guarded = [];
    
    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
    
}
