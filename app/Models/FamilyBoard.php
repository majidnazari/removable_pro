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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereSelectedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withoutTrashed()
 * @mixin \Eloquent
 */
class FamilyBoard extends Model
{
    use HasFactory, SoftDeletes;
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
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function Category()
    {
        return $this->belongsTo(CategoryContent::class, 'category_content_id');
    }


}
