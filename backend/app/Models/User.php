<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    protected $table = 'USERS';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'created_at',
        'last_login',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'created_by');
    }
}