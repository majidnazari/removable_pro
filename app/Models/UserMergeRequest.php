<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $request_is_read  0=not read 1=read
 * @property int $request_status  1=active 2=refused 3=suspend
 * @property int|null $merge_sender_id
 * @property int|null $merge_receiver_id
 * @property int $merge_is_read  0=not read 1=read
 * @property string|null $merge_expired_at
 * @property int $merge_status  1=active 2=refused 3=suspend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Person|null $mergeReceiver
 * @property-read \App\Models\Person|null $mergeSender
 * @property-read \App\Models\Person $receiver
 * @property-read \App\Models\Person $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest query() 
 * @method static \Database\Factories\UserMergeRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest onlyTrashed()
 
 * @property string|null $request_expired_at
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $user_sender_id
 * @property int $node_sender_id
 * @property int $user_receiver_id
 * @property int $node_receiver_id
 * @property int $request_status_sender  1=Active 2=Cancel 3=Suspend
 * @property string|null $request_sender_expired_at
 * @property int $request_status_receiver 1=Active 2=Refused 3=Suspend
 * @property string|null $merge_ids_sender
 * @property string|null $merge_ids_receiver
 * @property int $merge_status_sender  1=Active 2=Cancel 3=Suspend
 * @property string|null $merge_sender_expired_at
 * @property int $merge_status_receiver  1=Active 2=Refused 3=Suspend
 * @property int $status  1=Active 2=Inactive 3=Suspend 4=Complete
 
 * @mixin \Eloquent
 */
class UserMergeRequest extends Eloquent
{

    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'user_merge_requests';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';
    public const COLUMN_USER_SENDER_ID = 'user_sender_id';
    public const COLUMN_NODE_SENDER_ID = 'node_sender_id';
    public const COLUMN_USER_RECEIVER_ID = 'user_receiver_id';
    public const COLUMN_MERGE_SENDER_ID = 'merge_sender_id';
    public const COLUMN_MERGE_RECEIVER_ID = 'merge_receiver_id';


    protected $fillable = [
        'creator_id',
        'editor_id',
        'user_sender_id',
        'node_sender_id',
        'user_receiver_id',
        'node_receiver_id',
        'request_status_sender',
        "request_sender_expired_at",
        'request_status_receiver',
        'merge_ids_sender',
        'merge_ids_receiver',
        'merge_status_sender',
        "merge_sender_expired_at",
        'merge_status_receiver',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(Person::class, self::COLUMN_USER_SENDER_ID);
    }

    public function receiver()
    {
        return $this->belongsTo(Person::class, self::COLUMN_USER_RECEIVER_ID);
    }

    public function mergeSender()
    {
        return $this->belongsTo(Person::class, self::COLUMN_MERGE_SENDER_ID);
    }

    public function mergeReceiver()
    {
        return $this->belongsTo(Person::class, self::COLUMN_MERGE_RECEIVER_ID);
    }

}
