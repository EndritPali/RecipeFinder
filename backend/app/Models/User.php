<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * Represents an authenticated user.
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property string $created_at
 * @property string $last_login
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
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
        'deleted_at',
    ];

    /**
     * Get the recipes created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'created_by');
    }

    /**
     * Get the password for authentication.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
