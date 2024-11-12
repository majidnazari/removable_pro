<?php
namespace App\Rules\Share;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DateNotInFuture implements Rule
{
    protected $modelClass;
    protected $column;
    protected $recordId;
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @param  string  $modelClass  The model class to check
     * @param  string  $column      The column containing the date to check
     * @param  int     $recordId    The ID of the record to validate
     * @return void
     */
    public function __construct(string $modelClass, string $column, $recordId)
    {
        $this->modelClass = $modelClass;
        $this->column = $column;
        $this->recordId = $recordId;
        $this->errorMessage = 'An error occurred during validation.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Ensure the model exists and is an instance of Eloquent Model
        if (!is_subclass_of($this->modelClass, Model::class)) {
            $this->errorMessage = 'Invalid model specified for validation.';
            return false;
        }

        // Retrieve the record by ID
        $record = (new $this->modelClass)->find($this->recordId);

        // Ensure the record exists
        if (!$record) {
            $this->errorMessage = 'The specified record does not exist.';
            return false;
        }

        // Ensure the column exists in the record
        if (!isset($record->{$this->column})) {
            $this->errorMessage = 'The specified date column does not exist.';
            return false;
        }

        // Check if the date in the specified column is not in the future
        $dateValue = Carbon::parse($record->{$this->column});
        if ($dateValue->isFuture()) {
            $this->errorMessage = 'The date must not be in the future.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
