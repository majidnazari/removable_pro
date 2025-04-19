<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Traits\GetAllowedAllUsersInClan;
use App\Traits\GetAllUsersRelationInClanFromHeads;
use App\Traits\UpdateUserRelationTrait;
use Log;

trait AuthorizesUser
{
    use GetAllowedAllUsersInClan;
    use GetAllUsersRelationInClanFromHeads;
    use UpdateUserRelationTrait;
    protected $user;

    public function __construct()
    {
    }

    /**
     * Get a model based on user authorization.
     * 
     * @param string $modelClass The model class to be queried (e.g., 'App\Models\User')
     * @param array  $args       The query arguments
     * @return Model|null        The found model instance or null
     */
    protected function getModelByAuthorization(string $modelClass, array $args, bool $fetchAll = false, $seeAllClan = false)
    {
        $this->user = $this->getUser();


        $allusers = $this->getAllUsersInClanFromHeads($this->user->id);

        $specialRules = [
            'favorites' => ['creator_id'],
            'addresses' => ['creator_id'],
        ];

        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            $query = $modelClass::query();
        } else {
            $query = $modelClass::where(function ($q) use ($modelClass, $allusers, $specialRules, $seeAllClan) {

                $columns = $modelClass::getAuthorizationColumns();
                $table = (new $modelClass)->getTable();

                foreach ($columns as $column) {
                    if (Schema::hasColumn((new $modelClass)->getTable(), $column)) {
                        if ($seeAllClan && isset($specialRules[$table]) && in_array($column, $specialRules[$table])) {
                            $q->whereIn($column, $allusers);
                        } else {
                            // Default behavior
                            $q->where($column, $this->user->{$column} ?? $this->user->id);
                        }
                    }

                }
            });
        }

        if (!$fetchAll) {
            $query->where('id', $args['id']);
        }
        return $query;
    }
}
