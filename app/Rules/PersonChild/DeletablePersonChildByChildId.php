<?php

namespace App\Rules\PersonChild;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PersonMarriage;
use App\Models\PersonChild as PersonChildModel;

class DeletablePersonChildByChildId implements Rule
{
    protected $childId;

    public function __construct($childId)
    {
        $this->childId = $childId;
    }

    public function passes($attribute, $value)
    {
        // Check if the child is involved in any active marriage
        $isInMarriage = PersonMarriage::where('man_id', $this->childId)
            ->orWhere('woman_id', $this->childId);
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
