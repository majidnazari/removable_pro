<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyBoard> $FamilyBoards
 * @property-read int|null $family_boards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $Memories
 * @property-read int|null $memories_count
 * @method static \Database\Factories\CategoryContentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withoutTrashed()
 * @mixin \Eloquent
 */
class CategoryContent extends \Eloquent
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'status',
    ];

    public const TABLE_NAME = 'category_contents';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const CATEGORY_CONTENT_ID = 'category_content_id';

    protected $table = self::TABLE_NAME;

    public function FamilyBoards()
    {
        return $this->hasMany(FamilyBoard::class, self::CATEGORY_CONTENT_ID);
    }
    public function Memories()
    {
        return $this->hasMany(Memory::class, self::CATEGORY_CONTENT_ID);
    }
}
