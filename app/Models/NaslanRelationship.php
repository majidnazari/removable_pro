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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withoutTrashed()
 * @mixin \Eloquent
 */
class NaslanRelationship extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "title",
        "priority",
        "status",
    ] ;

    public const TABLE_NAME = 'category_contents';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;
}
