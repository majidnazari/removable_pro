<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Models\Person;


class CheckPersonOfEachUser implements Rule
{
    use AuthUserTrait;
    use FindOwnerTrait;
    protected $personId;

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    public function passes($attribute, $value)
    {
        // Check if the person has any active children
        return Person::where('id', $this->personId)->where('creator_id', $this->getUserId())->exists();
    }

    public function message()
    {
        return "this person is not your own!";
    }
}
