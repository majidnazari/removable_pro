<?php
namespace App\Rules\Share;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Exception;
class MatchCreator implements Rule
{
    protected $modelClass;
    protected $ids;
    protected $userId;
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @param  string  $modelClass  The model class to check
     * @param  array|int  $ids      The IDs to check
     * @return void
     */
    public function __construct(string $modelClass, $ids)
    {
        $this->modelClass = $modelClass;
        $this->ids = is_array($ids) ? $ids : [$ids]; // Ensure ids is an array
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

        // Get the authenticated user's ID
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;

        // Query the model for the specified IDs
        $modelInstance = new $this->modelClass;
        $records = $modelInstance->whereIn('id', $this->ids)->get();

        // Check that all records have matching creator_id
        foreach ($records as $record) {
            if ($record->creator_id !== $this->userId) {
                $this->errorMessage = 'You do not have permission to access one or more records.';
                return false;
            }
        }

        // Ensure that the count of records found matches the input count to check for invalid IDs
        if ($records->count() !== count($this->ids)) {
            $this->errorMessage = 'One or more IDs provided are invalid.';
            return false;
        }

        // Passes if all checks succeed
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
