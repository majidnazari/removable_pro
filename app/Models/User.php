<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

use Log;


/**
 * 
 *
 * @property int $id
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $mobile_is_verified
 * @property string|null $password
 * @property string|null $sent_code
 * @property \Illuminate\Support\Carbon|null $code_expired_at
 * @property int $password_change_attempts
 * @property string|null $last_password_change_attempt
 * @property \Illuminate\Support\Carbon|null $last_attempt_at
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $avatar
 * @property-read \App\Models\UserAnswer|null $Answer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteCreates
 * @property-read int|null $favorite_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteEdites
 * @property-read int|null $favorite_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryCreates
 * @property-read int|null $memory_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryEdites
 * @property-read int|null $memory_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserMobile> $Mobiles
 * @property-read int|null $mobiles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChildCreates
 * @property-read int|null $person_child_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChildEdites
 * @property-read int|null $person_child_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $PersonCreates
 * @property-read int|null $person_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $PersonEdites
 * @property-read int|null $person_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriageCreates
 * @property-read int|null $person_marriage_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriageEdites
 * @property-read int|null $person_marriage_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $Persons
 * @property-read int|null $persons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserSubscription> $Subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCodeExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastAttemptAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastPasswordChangeAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobileIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordChangeAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_code',
        'mobile',
        'email',
        'email_verified_at',
        'mobile_is_verified',
        'password',
        'sent_code',
        'code_expired_at',
        'user_attempt_time',
        'last_attempt_at',
        'blocked_attempts_count',
        'blocked_until',
        'status',
        'remember_token',
        'avatar',
    ];
    public const TABLE_NAME = 'users';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const USER_ID = 'user_id';
    public const MOBILE = 'mobile';
    
    protected $table = self::TABLE_NAME;

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
        return $this->where(SELF::MOBILE, [$username])->first();
    }

    public function Persons()
    {
        return $this->hasMany(person::class, SELF::USER_ID);
    }

    public function PersonCreates()
    {
        return $this->hasMany(person::class, SELF::CREATOR_ID);
    }
    public function PersonEdites()
    {
        return $this->hasMany(person::class, SELF::EDITOR_ID);
    }

    public function PersonMarriageCreates()
    {
        return $this->hasMany(PersonMarriage::class, SELF::CREATOR_ID);
    }
    public function PersonMarriageEdites()
    {
        return $this->hasMany(PersonMarriage::class, SELF::EDITOR_ID);
    }

    public function PersonChildCreates()
    {
        return $this->hasMany(personChild::class, SELF::CREATOR_ID);
    }
    public function PersonChildEdites()
    {
        return $this->hasMany(personChild::class, SELF::EDITOR_ID);
    }

    public function Subscriptions()
    {
        return $this->hasMany(UserSubscription::class, SELF::USER_ID);
    }

    public function FavoriteCreates()
    {
        return $this->hasMany(Favorite::class, SELF::CREATOR_ID);
    }
    public function FavoriteEdites()
    {
        return $this->hasMany(Favorite::class, SELF::EDITOR_ID);
    }

    public function MemoryCreates()
    {
        return $this->hasMany(Memory::class, SELF::CREATOR_ID);
    }
    public function MemoryEdites()
    {
        return $this->hasMany(Memory::class, SELF::EDITOR_ID);
    }
    public function Mobiles()
    {
        return $this->hasMany(UserMobile::class, SELF::USER_ID);
    }
    public function Answer()
    {
        return $this->hasOne(UserAnswer::class, SELF::USER_ID);
    }

    protected $casts = [
        'last_attempt_at' => 'datetime',
        'code_expired_at' => 'datetime', // if also necessary
        'blocked_until' => 'datetime',    // if also necessary
    ];

}
