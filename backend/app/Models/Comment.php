<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'description', 'posted_at', 'likes'];

    // Define the relationship to the User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
