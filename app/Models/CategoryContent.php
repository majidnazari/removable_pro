<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withoutTrashed()
 * @mixin \Eloquent
 
 */
class CategoryContent extends Eloquent
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'status',
    ];

    public const TABLE_NAME = 'category_contents';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';


    public function FamilyBoards()
    {
        return $this->hasMany(FamilyBoard::class, self::COLUMN_CATEGORY_CONTENT_ID);
    }
    public function Memories()
    {
        return $this->hasMany(Memory::class, self::COLUMN_CATEGORY_CONTENT_ID);
    }
}
