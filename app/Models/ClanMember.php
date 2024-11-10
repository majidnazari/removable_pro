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
 * @property int $clan_id
 * @property int|null $related_to
 * @property string $node_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Clan|null $FamilyRelations
 * @property-read \App\Models\Person|null $Person
 * @method static \Database\Factories\ClanMemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereClanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereNodeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereRelatedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember withoutTrashed()
 * @mixin \Eloquent
 */
class ClanMember extends Model
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'clan_id',
        'related_to',
        'biggest_person_id',
        'node_code',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'clan_members';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const BIGGEST_PERSON_ID = 'biggest_person_id';

    public const RELATED_ID = 'related_to';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, self::CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::EDITOR_ID);
    }


    public function FamilyRelations()
    {
        return $this->belongsTo(Clan::class, SELF::RELATED_ID);
    }
    public function Person()
    {
        return $this->belongsTo(Person::class, self::BIGGEST_PERSON_ID);
    }
}
