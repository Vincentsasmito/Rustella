<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'image_url'
    ];


    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'id');
    }
}