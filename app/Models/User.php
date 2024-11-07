<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

use Log;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['country_code', 'mobile', 'email', 'email_verified_at', 'mobile_is_verified', 'password', 'sent_code', 'code_expired_at', 'user_attempt_time', 'last_attempt_at', 'blocked_attempts_count', 'blocked_until',  'status', 'remember_token', 'avatar'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    }
    public function findForPassport($username)
    {

        //Log::info("the user name is:". $username);
        // return $this->where('mobile', $username)->where('status','Active')->where('mobile_is_verified',1)->first();
        //return $this->whereRaw("CONCAT(country_code, mobile) = ?", [$username])->first();
        return $this->where("mobile", [$username])->first();
    }

    public function Persons()
    {
        return $this->hasMany(person::class, 'user_id');
    }

    public function PersonCreates()
    {
        return $this->hasMany(person::class, 'creator_id');
    }
    public function PersonEdites()
    {
        return $this->hasMany(person::class, 'editor_id');
    }

    public function PersonMarriageCreates()
    {
        return $this->hasMany(PersonMarriage::class, 'creator_id');
    }
    public function PersonMarriageEdites()
    {
        return $this->hasMany(PersonMarriage::class, 'editor_id');
    }

    public function PersonChildCreates()
    {
        return $this->hasMany(personChild::class, 'creator_id');
    }
    public function PersonChildEdites()
    {
        return $this->hasMany(personChild::class, 'editor_id');
    }

    public function Subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }

    public function FavoriteCreates()
    {
        return $this->hasMany(Favorite::class, 'creator_id');
    }
    public function FavoriteEdites()
    {
        return $this->hasMany(Favorite::class, 'editor_id');
    }

    public function MemoryCreates()
    {
        return $this->hasMany(Memory::class, 'creator_id');
    }
    public function MemoryEdites()
    {
        return $this->hasMany(Memory::class, 'editor_id');
    }
    public function Mobiles()
    {
        return $this->hasMany(UserMobile::class, 'user_id');
    }
    public function Answer()
    {
        return $this->hasOne(UserAnswer::class, 'user_id');
    }

    protected $casts = [
        'last_attempt_at' => 'datetime',
        'code_expired_at' => 'datetime', // if also necessary
        'blocked_until' => 'datetime',    // if also necessary
    ];
    
}
