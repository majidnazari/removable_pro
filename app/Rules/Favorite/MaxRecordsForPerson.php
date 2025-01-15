<?php

namespace App\Rules\Favorite;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Favorite; // Replace with your actual model name
use App\Traits\FindOwnerTrait;
use Log;

class MaxRecordsForPerson implements Rule
{
    use FindOwnerTrait;
    protected $personId;
    protected $ignoreRecordId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $personId
     * @param  int|null  $ignoreRecordId
     */
    public function __construct($personId=null, $ignoreRecordId = null)
    {
        $this->personId = $personId ?? $this->findOwner()->id;
       // Log::info("the person id is:" .$this->personId);
        $this->ignoreRecordId = $ignoreRecordId;
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
        $query = Favorite::where('person_id', $this->personId);

        // Exclude the current record during update
        if ($this->ignoreRecordId) {
            $query->where('id', '!=', $this->ignoreRecordId);
        }

        //Log::info("the user count is:" . $query->count());
        return $query->count() < 10; // Allow only if less than 10 records exist
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This person cannot have more than 10 records.';
    }
}
