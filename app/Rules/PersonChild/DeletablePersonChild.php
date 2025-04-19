<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage;
use App\Models\PersonChild as PersonChildModel;

class DeletablePersonChild implements Rule
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        $personChild = PersonChildModel::find($this->id);

        // If the PersonChild record does not exist, return true to let the validator handle non-existence
        if (!$personChild) {
            return true;
        }

        // Get the child_id from the PersonChild record
        $childId = $personChild->child_id;

        // Check if the child is involved in any active marriage
        $isInMarriage = PersonMarriage::where('man_id', $childId)
            ->orWhere('woman_id', $childId);
        //->exists();

        // Find all marriage IDs where this person is either the man or woman
        $marriages = $isInMarriage->pluck('id');


        // Check if there are any PersonChild records linked to the marriages
        $hasChildren = PersonChildModel::whereIn('person_marriage_id', $marriages)->exists();

        // Allow deletion only if the child is not in any marriage and has no children
        return !($isInMarriage->exists()) || !$hasChildren;
    }

    public function message()
    {
        return "Cannot delete this person-child relationship because the child has active marriages or children.";
    }
}
