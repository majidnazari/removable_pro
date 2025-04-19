<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Person;
use App\Models\PersonMarriage;
use Carbon\Carbon;
use Log;

class RealisticParentChildAgeGap implements Rule
{
    protected $personMarriageId;

    public function __construct($personMarriageId)
    {
        $this->personMarriageId = $personMarriageId;
    }

    public function passes($attribute, $value)
    {
        $child = Person::find($value);
        $marriage = PersonMarriage::with(['Man', 'Woman'])->find($this->personMarriageId);

        // Check age gap between father and child
        if ($marriage && $child) {
            if ($marriage->Man) {
                $fatherAgeAtBirth = Carbon::parse($marriage->Man->birth_date)->diffInYears($child->birth_date);
                //              Log::info("the man birth dtae is:" .$fatherAgeAtBirth );
                if ($fatherAgeAtBirth < 13) {
                    return false;
                }
            }
            if ($marriage->Woman) {
                $motherAgeAtBirth = Carbon::parse($marriage->Woman->birth_date)->diffInYears($child->birth_date);
                //              Log::info("the Woman birth dtae is:" .$fatherAgeAtBirth );

                if ($motherAgeAtBirth < 13) {
                    return false;
                }
            }
        }
        return true;
    }

    public function message()
    {
        return 'The age difference between the parent and child is unrealistically small.';
    }
}
