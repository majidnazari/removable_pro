<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int|null $biggest_person_id
 * @property string $title
 * @property string $clan_exact_family_name
 * @property string $clan_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClanMember> $Members
 * @property-read int|null $members_count
 * @property-read \App\Models\Person|null $OldestAncestry
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\User|null $User
 * @method static \Database\Factories\ClanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereBiggestPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereClanCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereClanExactFamilyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan withoutTrashed()
 * @mixin \Eloquent
 */
class Clan extends \Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'biggest_person_id',
        'title',
        'clan_exact_family_name',
        'clan_code',
    ];
    use HasFactory, SoftDeletes;


    public const TABLE_NAME = 'clans';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const BIGGEST_PERSON_ID = 'biggest_person_id';
    public const CLAN_ID = 'clan_id';
    protected $table = self::TABLE_NAME;



    public function Person()
    {
        return $this->belongsTo(Person::class, self::CREATOR_ID);
    }
    public function OldestAncestry()
    {
        return $this->belongsTo(Person::class, self::BIGGEST_PERSON_ID);
    }

    public function User()
    {
        return $this->belongsTo(User::class, self::EDITOR_ID);
    }
    public function Members()
    {
        return $this->hasMany(ClanMember::class, self::CLAN_ID);
    }
}
