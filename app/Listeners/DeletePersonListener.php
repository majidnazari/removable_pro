<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use Illuminate\Support\Facades\Log;

use App\Models\FamilyEvent;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Models\Person;
use Exception;

class DeletePersonListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(PersonDeletedEvent $event)
    {
        $personId = $event->personId;

        $person = Person::where('id', $personId)->first();
        if (!$person) {
            throw new Exception("the person doesn't exist!.");
        }
        // Check if the person has any relationships
        $hasMarriage = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->exists();

        if ($hasMarriage) {
            throw new Exception('Person has existing relationships and cannot be deleted.');
        }

        $hasChildren = PersonChild::where('child_id', $personId)->exists();

        if ($hasChildren) {
            throw new Exception('Person has existing relationships and cannot be deleted.');
        }


        // If no relationships exist, proceed with deleting related events
        Person::where('id', $personId)->delete();
        Log::info("Deleted person node : $personId");
    }
}
