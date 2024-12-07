<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\GraphQL\Enums\Role;

use Eloquent;

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
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteCreates
 * @property-read int|null $favorite_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteEdites
 * @property-read int|null $favorite_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryCreates
 * @property-read int|null $memory_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryEdites
 * @property-read int|null $memory_edites_count
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
 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

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
        'password_change_attempts',
        'last_password_change_attempt',
        'last_attempt_at',
        'status',
        'role',
        'remember_token',
        'avatar',
    ];
    public const TABLE_NAME = 'users';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_MOBILE = 'mobile';


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
        return $this->where(self::COLUMN_MOBILE, [$username])->first();
    }

    public function Persons()
    {
        return $this->hasMany(person::class, self::COLUMN_USER_ID);
    }

    public function PersonCreates()
    {
        return $this->hasMany(person::class, self::COLUMN_CREATOR_ID);
    }
    public function PersonEdites()
    {
        return $this->hasMany(person::class, self::COLUMN_EDITOR_ID);
    }

    public function PersonMarriageCreates()
    {
        return $this->hasMany(PersonMarriage::class, self::COLUMN_CREATOR_ID);
    }
    public function PersonMarriageEdites()
    {
        return $this->hasMany(PersonMarriage::class, self::COLUMN_EDITOR_ID);
    }

    public function PersonChildCreates()
    {
        return $this->hasMany(personChild::class, self::COLUMN_CREATOR_ID);
    }
    public function PersonChildEdites()
    {
        return $this->hasMany(personChild::class, self::COLUMN_EDITOR_ID);
    }

    public function FavoriteCreates()
    {
        return $this->hasMany(Favorite::class, self::COLUMN_CREATOR_ID);
    }
    public function FavoriteEdites()
    {
        return $this->hasMany(Favorite::class, self::COLUMN_EDITOR_ID);
    }

    public function MemoryCreates()
    {
        return $this->hasMany(Memory::class, self::COLUMN_CREATOR_ID);
    }
    public function MemoryEdites()
    {
        return $this->hasMany(Memory::class, self::COLUMN_EDITOR_ID);
    }
    public function Notifs()
    {
        return $this->hasMany(Notif::class, self::COLUMN_USER_ID);
    }
   
    protected $casts = [
        'last_attempt_at' => 'datetime',
        'code_expired_at' => 'datetime', // if also necessary
        'blocked_until' => 'datetime',    // if also necessary
    ];

    public function isAdmin()
    {
        return $this->role === Role::Admin->value;
    }

    public function isSupporter()
    {
        return $this->role === Role::Supporter->value;
    }

    public function isUser()
    {
        return $this->role === Role::User->value;
    }

    public static function getAuthorizationColumns()
    {
        return [
            "country_code", 
            "mobile", 
             
        ];
    }

}
