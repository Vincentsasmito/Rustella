<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    //Can be removed, as Laravel already guesses table names. Kept here for clarity.
    protected $table = 'orders';

    protected $fillable = [
        'sender_email',
        'sender_phone',
        'sender_note',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'deliveries_id',
        'delivery_time',
        'progress',
        'cost',
        'user_id',
        'discount_id',
        'payment_url',
        'delivery_fee',
    ];

    //If discount doesn't exist, return - to display.
    public function getDiscountCode()
    {
        if (!$this->discount) {
            return 'No Discount Applied';
        }

        return $this->discount->code;
    }

    //Relation: Order belongs to discount
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class, 'deliveries_id', 'id');
    }

    public function recalculateCost()
    {
        $cost = 0;

        foreach ($this->orderProducts as $op) {
            $product = $op->product;
            $flowerProducts = FlowerProduct::where('product_id', $op->product_id)->get();

            // Calculate the cost to make 1 unit of the product
            $unitCost = $flowerProducts->reduce(function ($sum, $fp) {
                return $sum + ($fp->flower->price * $fp->quantity);
            }, 0);

            //Calculate packaging cost per unit
            if($product->packaging_id)
            {
                $packaging = Packaging::find($product->packaging_id);
                if($packaging){
                    $unitCost += $packaging->price;
                }
            }

            // Multiply by how many units were ordered
            $cost += $unitCost * $op->quantity;
        }
        $deliveryFee = Delivery::where('id', $this->deliveries_id)->value('fee') ?? 0;

        $this->cost = $cost + $deliveryFee;
        $this->save();
    }
    //PROD Admin Dashboard getSales
    public function getTotalAmountAttribute()
    {
        // Assumes $this->orderProducts is already loaded (or will lazy-load)
        return $this->orderProducts
            ->sum(fn($item) => $item->quantity * $item->price);
    }
}
