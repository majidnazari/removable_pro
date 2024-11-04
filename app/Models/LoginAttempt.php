<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
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
