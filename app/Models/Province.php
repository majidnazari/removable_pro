<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $country_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country $Country
 * @method static \Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withoutTrashed()
 * @mixin \Eloquent
 */
class Province extends Model
{
    protected $fillable = [
        'country_id',
        'title',
        'code',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'provinces';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

}


