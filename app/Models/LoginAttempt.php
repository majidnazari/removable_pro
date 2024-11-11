<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $ip_address
 * @property int $today_attempts
 * @property string|null $attempt_date
 * @property string|null $expire_blocked_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereAttemptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereExpireBlockedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereTodayAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereUserId($value)
 * @mixin \Eloquent
 */
class LoginAttempt extends  \Eloquent
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'today_attempts',
        'total_attempts',
        'attempt_date',
        'expire_blocked_time',
        'number_of_blocked_times',
    ];

    public static function recordAttempt($userId, $ipAddress)
    {
        $loginAttempt = self::firstOrCreate(
            ['user_id' => $userId, 'ip_address' => $ipAddress],
            ['attempt_date' => Carbon::today()->toDateString()]
        );

        // Reset daily attempts if it's a new day
        if ($loginAttempt->attempt_date != Carbon::today()->toDateString()) {
            $loginAttempt->today_attempts = 0;
            $loginAttempt->attempt_date = Carbon::today()->toDateString();
        }

        $loginAttempt->today_attempts += 1;
        $loginAttempt->total_attempts += 1;
        $loginAttempt->save();

        return $loginAttempt;
    }
}
