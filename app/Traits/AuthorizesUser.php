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

        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            $query = $modelClass::query();
        } 
        else {
            $query = $modelClass::where(function ($q) use ($modelClass) {
               
                $columns = $modelClass::getAuthorizationColumns();
          
            foreach ($columns as $column) {
                // Check if the column exists on the model's table
                if (Schema::hasColumn((new $modelClass)->getTable(), $column)) {
                   // Log::info("Column exists on model table: " . $column);
                    //$q->where($column, $this->user->id);
                    $q->where($column, $this->user->{$column});
                } 
                else {
                    // Log a warning if the column doesn't exist
                    //Log::warning("Column does NOT exist on model table: " . $column);
                }
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
        return  $query;
    }
}
