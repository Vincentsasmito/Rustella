<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    public $timestamps = false;

    protected $guard = 'admin';

    protected $fillable = ['username', 'password'];

    protected $hidden = ['password'];
}
