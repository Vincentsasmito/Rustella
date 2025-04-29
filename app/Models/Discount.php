<?php

namespace App\Models;

//Imports
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Discount extends Model
{
    //To prevent auto-addition of Timestamps by Eloquent. Will cause an Error in DB as no column exists.
    public $timestamps = false;

    //Can be removed, as Laravel already guesses table names. Kept here for clarity.
    protected $table = 'discounts';
    //What can be filled
    protected $fillable = [
        'code',
        'percent',
        'max_value',
        'min_purchase',
    ];

    //Relation: Discount has many orders.
    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'discount_id', 'id');
    }
}
