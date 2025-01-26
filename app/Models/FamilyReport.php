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
 * @property int|null $men_count
 * @property int|null $women_count
 * @property int|null $oldest
 * @property int|null $oldest_dead
 * @property int|null $yongest
 * @property string|null $marriage_count
 * @property string|null $divorce_count
 * @property string|null $lastupdate
 * @property int $change_flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Database\Factories\FamilyReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyReport query()

 * @mixin \Eloquent
 */
class FamilyReport extends Eloquent
{
    protected $fillable = [
        'clan_id',
        'men_count',
        'women_count',
        'oldest',
        'oldest_dead',
        'yongest',
        'marriage_count',
        'divorce_count',
        'change_flag',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'family_reports';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
}
