<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $category_content_id
 * @property string $title
 * @property string $selected_date
 * @property string|null $file_path
 * @property string $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CategoryContent $Category
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @method static \Database\Factories\FamilyBoardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard query()

 * @mixin \Eloquent
 */
class FamilyBoard extends Eloquent
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'creator_id',
        'editor_id',
        'category_content_id',
        'title',
        'selected_date',
        'file_path',
        'description',
        'status',
    ];
    public const TABLE_NAME = 'family_boards';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }

    public function Category()
    {
        return $this->belongsTo(CategoryContent::class, self::COLUMN_CATEGORY_CONTENT_ID);
    }


}
