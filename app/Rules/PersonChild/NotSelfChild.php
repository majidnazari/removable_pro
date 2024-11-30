<?php
namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage; // Make sure this is the correct model

class NotSelfChild implements Rule
{
    protected $personMarriageId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $personMarriageId  The ID of the marriage to validate against
     * @return void
     */
    public function __construct($personMarriageId)
    {
        $this->personMarriageId = $personMarriageId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value  The ID of the child
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Retrieve the marriage record by ID
        $marriage = PersonMarriage::find($this->personMarriageId);

        // If marriage record is not found, fail the validation
        if (!$marriage) {
            return false;
        }

        // Check if child_id (value) matches man_id (father) or woman_id (mother)
        return $value != $marriage->man_id && $value != $marriage->woman_id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person cannot be their own child or their parent’s child.';
    }
}
