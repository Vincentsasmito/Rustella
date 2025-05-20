<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suggestion extends Model
{
    //declares table to be used
    protected $table = 'suggestions';
    //self-explanatory
    protected $fillable = ['message', 'user_id', 'product_id', 'rating', 'order_id', 'type'];

    public function user(): BelongsTo
    {
        return ($this->belongsTo(User::class));
    }
    
    public function product(): BelongsTo
    {
        return ($this->belongsTo(Product::class));
    }
}
