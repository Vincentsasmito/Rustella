<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // If your pivot table doesn't have timestamps
    public $timestamps = false;

    // Explicit table name (snake_case plural)
    protected $table = 'products';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'packaging_id',
        'in_stock',
    ];


    public function flowerProducts():HasMany
    {
        return $this->hasMany(FlowerProduct::class, 'product_id');
    }

    public function packaging():BelongsTo
    {
        return $this->belongsTo(Packaging::class, 'packaging_id', 'id');
    }

    public function suggestion():HasMany
    {
        return $this->hasMany(Suggestion::class);
    }
}