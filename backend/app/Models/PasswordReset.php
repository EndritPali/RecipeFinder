<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset
 *
 * Represents a password reset token for a user.
 *
 * @property int $id
 * @property int $user_id
 * @property string $reset_token
 * @property string $expires_at
 * @property string $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class PasswordReset extends Model
{
    protected $table = 'PASSWORD_RESETS';
    public $timestamps = false;

    protected $fillable = ['user_id', 'reset_token', 'expires_at', 'created_at'];

    /**
     * Get the user associated with the password reset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
