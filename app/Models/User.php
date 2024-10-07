<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['mobile', 'password', 'sent_code', 'expired_at', 'mobile_is_veryfied', 'status', 'remember_token', 'avatar'];

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

    public function persons()
    {
        return $this->hasMany(person::class, 'user_id');
    }

    public function person_creates()
    {
        return $this->hasMany(person::class, 'creator_id');
    }
    public function person_edites()
    {
        return $this->hasMany(person::class, 'editor_id');
    }

    public function person_spouse_creates()
    {
        return $this->hasMany(personSpouse::class, 'creator_id');
    }
    public function person_spouse_edites()
    {
        return $this->hasMany(personSpouse::class, 'editor_id');
    }

    public function person_child_creates()
    {
        return $this->hasMany(personChild::class, 'creator_id');
    }
    public function person_child_edites()
    {
        return $this->hasMany(personChild::class, 'editor_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }

    public function favorite_creates()
    {
        return $this->hasMany(Favorite::class, 'creator_id');
    }
    public function favorite_edites()
    {
        return $this->hasMany(Favorite::class, 'editor_id');
    }

    public function memory_creates()
    {
        return $this->hasMany(Memory::class, 'creator_id');
    }
    public function memory_edites()
    {
        return $this->hasMany(Memory::class, 'editor_id');
    }
}
