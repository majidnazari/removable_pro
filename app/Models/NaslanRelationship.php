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
 * @property string $title
 * @property int $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\NaslanRelationshipFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship query()
 
 * @mixin \Eloquent
 */
class NaslanRelationship extends  Eloquent
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "priority",
        "status",
    ] ;

    public const TABLE_NAME = 'naslan_relations';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';
  
    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';
}
