<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;


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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUpdatedAt($value)
 * @method static \Database\Factories\UserMergeRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereHassan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withoutTrashed()
 * @property string|null $request_expired_at
 * @mixin \Eloquent
 */
class UserMergeRequest extends \Eloquent
{

    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'user_merge_requests';
    public const ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
    public const USER_SENDER_ID = 'user_sender_id';
    public const NODE_SENDER_ID = 'node_sender_id';
    public const USER_Receiver_ID = 'user_receiver_id';
    public const MERGE_SENDER_ID = 'merge_sender_id';
    public const MERGE_Receiver_ID = 'merge_receiver_id';
    protected $table = self::TABLE_NAME;


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
        return $this->belongsTo(Person::class, self::USER_SENDER_ID);
    }

    public function receiver()
    {
        return $this->belongsTo(Person::class, self::USER_Receiver_ID);
    }

    public function mergeSender()
    {
        return $this->belongsTo(Person::class, self::MERGE_SENDER_ID);
    }

    public function mergeReceiver()
    {
        return $this->belongsTo(Person::class, self::MERGE_Receiver_ID);
    }

}
