<?php

namespace App\Models;

use App\GraphQL\Enums\NotifStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int $user_id
 * @property string|null $message
 * @property int $notif_status  1=Read 2=NotRead
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif query()
 
 * @mixin \Eloquent
 */
class Notif extends Eloquent
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "creator_id",
        "user_id",
        "notifiable_id",
        "notifiable_type",
        "message",
        "notif_status",
    ] ;

    public const TABLE_NAME = 'notifs';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';

    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_USER_ID = 'user_id';

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function RelatedUser()
    {
        return $this->belongsTo(User::class, self::COLUMN_USER_ID);
    }
    public function notifiable()
    {
        return $this->morphTo();
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }

    public function scopeNotRead($query)
    {
        return $query->where('notif_status', NotifStatus::NotRead);
    }
}
