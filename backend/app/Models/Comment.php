<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * Represents a comment on a recipe.
 *
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property string $posted_at
 * @property int $likes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static static create(array $attributes = [])
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'description', 'posted_at', 'likes'];

    /**
     * Get the user that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
