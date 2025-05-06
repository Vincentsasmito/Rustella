<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flower extends Model
{
    public $timestamps = false;

    //Can be removed, as Laravel already guesses table names. Kept here for clarity.
    protected $table = 'flowers';

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'price',
    ];

    public function Packaging(): BelongsTo
    {
        return $this->belongsTo(FlowerProduct::class, 'flower_id', 'id');
    }

    
}
