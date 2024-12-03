<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Log;

trait SearchQueryBuilder
{
    /**
     * Apply dynamic filters and ordering to a query based on arguments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearchFilters($query, array $args)
    {
        $table = $query->getModel()->getTable();
        $columns = Schema::getColumnListing($table); // Get all columns of the table

        // Apply filters for each argument that matches a column
        foreach ($args as $key => $value) {
            if (in_array($key, $columns) && !is_null($value)) {
                // Get column type using the database schema
                $columnType = Schema::getColumnType($table, $key);
                // If the column is a string, apply a LIKE query
                if ($columnType == 'varchar' || $columnType == 'text') {
               // Log::info("the column name is:" . $key . " and the type of column is :" .  $columnType);

                    $query->where($key, 'LIKE', '%' . $value . '%'); // Use LIKE for string columns
                } else {
                    $query->where($key, $value); // Exact match for other types
                }
            }
        }

        // // Apply ordering if 'orderBy' is provided
        // if (isset($args['orderBy']) && is_array($args['orderBy'])) {
        //     foreach ($args['orderBy'] as $order) {
        //         $query->orderBy($order['column'], $order['order']);
        //     }
        // }

        return $query;
    }
}
