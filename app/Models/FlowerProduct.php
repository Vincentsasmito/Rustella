<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowerProduct extends Model
{
    // If your pivot table doesn't have timestamps
    public $timestamps = false;

    // Explicit table name (snake_case plural)
    protected $table = 'flower_products';

    // Fillable fields for mass assignment
    protected $fillable = [
        'product_id',
        'flower_id',
        'quantity',
    ];

    /**
     * Get the order that owns this order-product pivot.
     */
    public function flower(): BelongsTo
    {
        return $this->belongsTo(Flower::class, 'flower_id', 'id');
    }

    /**
     * Get the product that this order-product pivot refers to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}