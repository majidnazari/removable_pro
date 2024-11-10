<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $remain_volume (MB)
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\Subscription $Subscription
 * @method static \Database\Factories\UserSubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereRemainVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription withoutTrashed()
 * @mixin \Eloquent
 */
class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'start_date',
        'end_date',
        'remain_volume',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'user_subscriptions';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function Subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    // public function editor()
    // {
    //     return $this->belongsTo(User::class, 'editor_id');
    // }
}
