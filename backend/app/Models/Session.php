<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'SESSIONS';
    public $timestamps = false;

    protected $fillable = ['user_id', 'token', 'expires_at', 'created_at'];

    public function user()
    {
       return $this->belongsTo(User::class, 'user_id');
    }
}
