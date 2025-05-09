<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    protected $fillable = ['email', 'name', 'role', 'password'];

    protected $hidden = ['password'];

    public function suggestion(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
