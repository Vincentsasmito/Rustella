<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    //declares table to be used
    protected $table = 'suggestions';
    //self-explanatory
    protected $fillable = ['message'];
}
