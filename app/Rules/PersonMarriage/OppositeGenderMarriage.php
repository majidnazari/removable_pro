<?php

namespace App\Rules\PersonMarriage;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Person;
use Log;

class OppositeGenderMarriage implements Rule
{
    protected $userId;

    protected $manId;
    protected $womanId;

    /**
     * Create a new rule instance.
     *
     * @param  int|null  $manId
     * @param  int|null  $womanId
     * @return void
     */
    public function __construct($manId, $womanId)
    {
        $this->manId = $manId;
        $this->womanId = $womanId;
        $this->errorMessage = 'An unknown error occurred.';
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
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;

       // Log::info("the is is :". $userId);

        // If either manId or womanId is null, the rule passes (considered valid if only one ID is provided)
        if (is_null($this->manId) || is_null($this->womanId)) {
            return true;
        }

        // Retrieve the gender of each person
        $man = Person::find($this->manId);
        $woman = Person::find($this->womanId);


        // Check if both persons have the same creator_id as the logged-in user
        if ($man->creator_id !==  $this->userId || $woman->creator_id !==  $this->userId) {
            $this->errorMessage = 'You can only marry persons you have created.';
            return false;
        }

        // Ensure both people exist and have a defined gender
        if (!$man || !$woman || is_null($man->gender) || is_null($woman->gender)||($man->gender===$woman->gender)) {
            $this->errorMessage = 'Two people of the same gender cannot marry each other.';
            return false;
        }

        // Check that genders are opposite: 0 = male, 1 = female
        //return ($man->gender === 0 && $woman->gender === 1) || ($man->gender === 1 && $woman->gender === 0);
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

