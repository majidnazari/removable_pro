<?php

namespace App\Listeners;

use App\Events\PersonDeletedEvent;
use Illuminate\Support\Facades\Log;

use App\Models\FamilyEvent;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Models\Person;
use Exception;
use App\Exceptions\CustomValidationException;


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
            throw new CustomValidationException("the person doesn't exist!.", "فرد وجود ندارد!", 404);

            //throw new Exception("the person doesn't exist!.");
        }
        // Check if the person has any relationships
        $hasMarriage = PersonMarriage::where($person->gender == 1 ? 'man_id' : 'woman_id', $personId)
            ->exists();

        if ($hasMarriage) {
            throw new CustomValidationException("Person has existing relationships and cannot be deleted.", "شخص دارای روابط موجود است و نمی توان آن را حذف کرد.", 403);

            //throw new Exception('Person has existing relationships and cannot be deleted.');
        }

        $hasChildren = PersonChild::where('child_id', $personId)->exists();

        if ($hasChildren) {
            throw new CustomValidationException("Person has existing relationships and cannot be deleted.", "شخص دارای روابط موجود است و نمی توان آن را حذف کرد.", 403);

            //throw new Exception('Person has existing relationships and cannot be deleted.');
        }

        $deletedCount = Person::where('id', $personId)->update(['deleted_at' => now()]);

        if ($deletedCount === 0) {
            Log::warning("No Person records found for person ID: $personId");
        } else {
//           Log::info("Soft deleted {$deletedCount} Person records for person ID: $personId");
        }
    }
}
