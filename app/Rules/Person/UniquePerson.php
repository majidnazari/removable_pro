<?php

namespace App\Rules\Person;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;

use Log;

class UniquePerson implements Rule
{
    protected $firstName;
    protected $lastName;
    protected $birthDate;
    protected $id;  // The ID to exclude from the uniqueness check

    /**
     * Create a new rule instance.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $birthDate
     * @param int|null $id
     */
    public function __construct($firstName, $lastName, $birthDate, $id = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->id = $id;
       // Log::info("the args of rule constructor are  :" . json_encode($this) );

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
        $query = Person::where('first_name', $this->firstName)
            ->where('last_name', $this->lastName)
            ->where('birth_date', $this->birthDate);

           // Log::info("the args of rule passes are  :" . json_encode($this) );

        // Exclude the current person from the check if updating
        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }

        return !$query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person with the same first name, last name, and birth date already exists.';
    }
}
