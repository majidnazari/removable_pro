<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PersonDeletedEvent
{
    use Dispatchable, SerializesModels;

    public $personId;

    public function __construct($personId)
    {
        $this->personId = $personId;
    }
}
