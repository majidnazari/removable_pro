<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\GraphQL\Enums\Role;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail query()
 * @mixin \Eloquent
 */
class UserDetail extends Model
{
    //
    protected $fillable = [
        'creator_id',
        'editor_id',
        'last_seen_family_board_id',
        'mobile',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'user_details';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_LAST_SEEN_FAMILY_BOARD_ID = 'last_seen_family_board_id';


    public function Craetor()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function FamilyBoard()
    {
        return $this->belongsTo(FamilyBoard::class, self::COLUMN_LAST_SEEN_FAMILY_BOARD_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}
