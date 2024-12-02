<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

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
                $query->where($key, $value);
            }
        }

        // Apply ordering if 'orderBy' is provided
        if (isset($args['orderBy']) && is_array($args['orderBy'])) {
            foreach ($args['orderBy'] as $order) {
                $query->orderBy($order['column'], $order['order']);
            }
        }

        return $query;
    }
}
