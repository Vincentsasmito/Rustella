<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    // Table name is the plural of the model by default (stock_transactions), so no need to override $table.

    // We only have created_at; no updated_at column.
    public $timestamps = false;
    const UPDATED_AT = null;
    // Explicit table name (snake_case plural)
    protected $table = 'stock_transactions';
    // Allow mass‐assignment on these fields:
    protected $fillable = [
        'order_id',
        'flower_id',
        'flower_name',
        'packaging_id',
        'packaging_name',
        'type',
        'quantity',
        'price',
        'created_at',
    ];

    // Cast created_at to a Carbon instance
    protected $casts = [
        'created_at' => 'datetime',
        'price'      => 'decimal:2',
    ];

    /**
     * The order that this transaction belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * (Optional) The flower involved, if any.
     */
    public function flower()
    {
        return $this->belongsTo(Flower::class);
    }

    /**
     * (Optional) The packaging involved, if any.
     */
    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }
}
