<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Log;

trait AuthorizesUser
{
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
    protected function getModelByAuthorization(string $modelClass, array $args, bool $fetchAll = false)
    {
        $this->user = $this->getUser();

        Log::info("User role is: " . $this->user->role . " and condition is: " . ($this->user->isAdmin() || $this->user->isSupporter()));

        // Check if the user is admin or supporter
        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            // Admins and Supporters can access all models
            $query = $modelClass::query();
        } else {
            // For normal users, apply the dynamic column checks
            $query = $modelClass::where(function ($q) use ($modelClass) {
                // Get the list of columns from the model that need to be checked
                $columns = $modelClass::getAuthorizationColumns();
                
                Log::info("Columns to check in user model: " . json_encode($columns));

            // Loop through the columns and apply the authorization logic dynamically
            foreach ($columns as $column) {
                // Check if the column exists on the model's table
                if (Schema::hasColumn((new $modelClass)->getTable(), $column)) {
                    Log::info("Column exists on model table: " . $column);
                    $q->where($column, $this->user->id);
                } else {
                    // Log a warning if the column doesn't exist
                    Log::warning("Column does NOT exist on model table: " . $column);
                }
            }
            });

        }
        

        // If it's a single record fetch, apply the 'id' filter
        if (!$fetchAll) {
            $query->where('id', $args['id']);
        }

        Log::info("fetchAll is:".$fetchAll."the query is:" . $query->ToSql());

        // // If we're fetching a collection, apply pagination
        // if (isset($args['first']) && isset($args['page'])) {
        //     $query->skip(($args['page'] - 1) * $args['first'])->take($args['first']);
        // }

        // Return the result(s)
        return $fetchAll ? $query : $query->first();
    }
}
