<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public $timestamps = false;

    //Can be removed, as Laravel already guesses table names. Kept here for clarity.
    protected $table = 'orders';

    protected $fillable = [
        'sender_email',
        'sender_phone',
        'sender_note',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_city',
        'delivery_time',
        'delivery_details',
        'progress',
        'discount_id',
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
}
