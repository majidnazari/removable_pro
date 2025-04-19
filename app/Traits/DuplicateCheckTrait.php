<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomValidationException;
use Log;

trait DuplicateCheckTrait
{
    /**
     * Check for duplicate records in a model based on provided columns and values.
     * Allows ignoring specific columns dynamically and normalizes data.
     *
     * @param Model $model
     * @param array $columnsAndValues  // Associative array of column => value
     * @param array $exceptionColumns  // Columns to ignore during validation
     * @param int|null $excludeId      // Exclude ID for updates
     * @throws ValidationException
     */
    public function checkDuplicate(Model $model, array $columnsAndValues, array $exceptionColumns = [], ?int $excludeId = null)
    {
        $query = $model->newQuery();

        // Add where conditions, ignoring exception columns
        foreach ($columnsAndValues as $column => $value) {
            if (!in_array($column, $exceptionColumns)) {
                $normalizedValue = is_numeric($value) ? +$value : $value;
                $query->where($column, $normalizedValue);
            }
        }

        // Exclude the current record if updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Generate the final query with bound parameters
        $sqlWithBindings = $this->replaceQueryBindings($query->toSql(), $query->getBindings());

        // Log the final query
//      Log::info('Final Query: ' . $sqlWithBindings);

        // If duplicate exists, throw an error
        // if ($query->exists()) {
        //     $filteredColumns = array_diff(array_keys($columnsAndValues), $exceptionColumns);
        //     $columns = implode(', ', $filteredColumns);
        //     throw ValidationException::withMessages([
        //         "duplicate" => "A record with the same {$columns} already exists."
        //     ]);
        // }

        if ($query->exists()) {
            $filteredColumns = array_diff(array_keys($columnsAndValues), $exceptionColumns);
            $columns = implode(', ', $filteredColumns);
            $message = "A record with the same {$columns} exists.";
            $endUserMessage="رکوردی با اطلاعات {$columns} قبلا ثبت شده است.";
            $statusCode=422;
    
            throw new CustomValidationException($message, $endUserMessage, $statusCode);
        }
    }

    /**
     * Replace query placeholders with actual bindings.
     *
     * @param string $query
     * @param array $bindings
     * @return string
     */
    protected function replaceQueryBindings(string $query, array $bindings): string
    {
        foreach ($bindings as $binding) {
            // Escape binding values for SQL
            $binding = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            $query = preg_replace('/\?/', $binding, $query, 1);
        }
        return $query;
    }

}
