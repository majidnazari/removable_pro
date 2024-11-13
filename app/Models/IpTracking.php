<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property string $ip
 * @property string|null $date_attemp
 * @property int $today_attemp
 * @property int $total_attemp
 * @property string $status
 * @property string|null $expire_blocked_time
 * @property int $number_of_blocked_times
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\IpTrackingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereDateAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereExpireBlockedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereNumberOfBlockedTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereTodayAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereTotalAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking withoutTrashed()
 * @mixin \Eloquent
 */
class IpTracking extends \Eloquent
{
    protected $fillable = [
        'ip',
        'date_attemp',
        'today_attemp',
        'total_attemp',
        'status',
        'expire_blocked_time',
        'number_of_blocked_times',
    ];
    use HasFactory, SoftDeletes;
}
