<?php

namespace App\Rules\Person;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Log;

class ValidDeathDate implements Rule
{
    protected $birthDateMan;
    protected $birthDateWoman;
    protected $marriageDate;
    protected $divorceDate;

    public function __construct($birthDateMan = null, $birthDateWoman = null, $marriageDate = null, $divorceDate = null)
    {
//      Log::info("construct ValidDeathDate man birth date {$birthDateMan} and woman {$birthDateWoman} and marriage is {$marriageDate} and divorce  is {$divorceDate}");

        $this->birthDateMan = $birthDateMan ? Carbon::parse($birthDateMan) : null;
        $this->birthDateWoman = $birthDateWoman ? Carbon::parse($birthDateWoman) : null;
        $this->marriageDate = $marriageDate ? Carbon::parse($marriageDate) : null;
        $this->divorceDate = $divorceDate ? Carbon::parse($divorceDate) : null;
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

//       Log::info("the log of passes death date value is {$value}");
        if (!$value) {
            return true; // If no death_date is provided, validation should pass
        }

        $deathDate = Carbon::parse($value);
//      Log::info("the death date is {$deathDate} and  man birth date {$this->birthDateMan} and woman {$this->birthDateWoman} and m date is {$this->marriageDate} and divore date is {$this->divorceDate}");

        // 1. Death date must be after or equal to birth date
        if ($this->birthDateMan && $deathDate->lessThan($this->birthDateMan)) {

//           Log::info("the death date is {$deathDate} and man date i {$this->birthDateMan}" );
            return false;
        }
        // 2. Death date must be after or equal to birth date
        if ($this->birthDateWoman && $deathDate->lessThan($this->birthDateWoman)) {
//           Log::info("the death date is {$deathDate} and woman date i {$this->birthDateWoman}" );

            return false;
        }

        // 3. If marriage date exists, death date must be after or equal to it
        if ($this->marriageDate && $deathDate->lessThan($this->marriageDate)) {
//           Log::info("the death date is {$deathDate} and marriage date i {$this->marriageDate}" );

            return false;
        }

        // 4. If divorce date exists, death date must be after or equal to it
        if ($this->divorceDate && $deathDate->lessThan($this->divorceDate)) {
//           Log::info("the death date is {$deathDate} and diorce date i {$this->divorceDate}" );

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
        return "The death date must follow the sequence: birth_date ≤ marriage_date ≤ divorce_date ≤ death_date.";
    }
}
