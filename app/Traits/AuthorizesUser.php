<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Traits\GetAllowedAllUsersInClan;
use Log;

trait AuthorizesUser
{
    use GetAllowedAllUsersInClan;
    protected $user;

    public function __construct()
    {
        //$this->user = $this->getUser();
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
        $allusers = $this->getAllowedUserIds();

        //Log::info("the seeAllClan is : " . $seeAllClan);
       // Log::info("the  allusers is : " . json_encode( $allusers));
        // Define configurable table-column mappings for special handling
        $specialRules = [
            'favorites' => ['creator_id'],
            'addresses' => ['creator_id'],
            // Add other tables and columns as needed
            // 'another_table' => ['some_column'],
        ];

        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            $query = $modelClass::query();
        } else {
            $query = $modelClass::where(function ($q) use ($modelClass, $allusers, $specialRules, $seeAllClan) {

                $columns = $modelClass::getAuthorizationColumns();
                $table = (new $modelClass)->getTable();

                foreach ($columns as $column) {
                    // Check if the column exists on the model's table
                    if (Schema::hasColumn((new $modelClass)->getTable(), $column)) {
                        //Log::info("the users are:" . json_encode($seeAllClan). " column is :" .$column . " and  specialRulestable]" . json_encode($specialRules[$table])  );
                        if ($seeAllClan && isset($specialRules[$table]) && in_array($column, $specialRules[$table])) {
                           //Log::info("the users are:" .json_encode($allusers ));
                            // Apply special rule for this table and column
                            $q->whereIn($column, $allusers);
                        } else {
                            // Default behavior
                            $q->where($column, $this->user->{$column} ?? $this->user->id);
                        }
                        //Log::info("Column exists on model table: " . $column);
                        //$q->where($column, $this->user->id);
                        //$q->where($column, $this->user->{$column});
                        // $q->where($column, $this->user->{$column} ?? $this->user->id);
                    }
                    //else {
                    // Log a warning if the column doesn't exist
                    //Log::warning("Column does NOT exist on model table: " . $column);
                    //}
                }
            });
        }

        if (!$fetchAll) {
            $query->where('id', $args['id']);
        }

        // $querylog = $query->toBase(); // Get the base query builder (which contains the bindings)

        // $sql = $querylog->toSql(); // Get the SQL query with placeholders (?)

        // $bindings = $querylog->getBindings(); // Get the bindings (parameters)

        // $fullQuery = vsprintf(str_replace('?', '%s', $sql), $bindings); // Replace placeholders with actual values

        // Log::info("The query is: " . $fullQuery);


        //return $fetchAll ? $query : $query->first();
        return $query;
    }
}
