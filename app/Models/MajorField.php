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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MajorField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MajorField extends Eloquent
{

    protected $fillable = [

        'title',

    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'major_fields';
    protected $table = self::TABLE_NAME;

}
