<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int $user_id
 * @property string $mobile
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User $User
 * @method static \Database\Factories\UserMobileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile withoutTrashed()
 * @mixin \Eloquent
 */
class UserMobile extends Model
{
    protected $fillable = [
        'creator_id',
        'user_id',
        'mobile',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'user_mobiles';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
