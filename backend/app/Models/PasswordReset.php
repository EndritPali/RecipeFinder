<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'PASSWORD_RESETS';
    public $timestamps = false;

    protected $fillable = ['user_id', 'reset_token', 'expires_at', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
