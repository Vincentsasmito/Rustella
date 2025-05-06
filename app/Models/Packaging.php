<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packaging extends Model
{
    public $timestamps = false;

    //Can be removed, as Laravel already guesses table names. Kept here for clarity.
    protected $table = 'packagings';

    protected $fillable = [
        'name',
        'price',
    ];

    public function Packaging(): HasMany
    {
        return $this->hasMany(Product::class, 'packaging_id', 'id');
    }
    
}
