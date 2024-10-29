<?php

namespace App\Rules\PersonMarriage;

use Illuminate\Contracts\Validation\Rule;

class NotSelfMarriage implements Rule
{
    protected $manId;
    protected $womanId;

    public function __construct($manId, $womanId)
    {
        $this->manId = $manId;
        $this->womanId = $womanId;
    }

    public function passes($attribute, $value)
    {
        return $this->manId !== $this->womanId;
    }

    public function message()
    {
        return 'A person cannot be married to themselves.';
    }
}
