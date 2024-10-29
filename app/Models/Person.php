<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Log;


class Person extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'node_code', 'node_level_x', 'node_level_y', 'first_name', 'last_name', 'birth_date', 'death_date', 'is_owner','gendar', 'status'];
    use HasFactory , SoftDeletes;


    public function Creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class,'editor_id');
    }


    public function PersonMarriages()
    {
        // return $this->hasMany(PersonMarriage::class, 'person_id')->orwhere('spouse_id',$this->id);
         return $this->hasMany(PersonMarriage::class, 'man_id')->orWhere('woman_id',$this->id);

    }
  
    public function Addresses()
    {
        return $this->hasMany(Address::class, 'address_id');
    }

    public function Memories()
    {
        return $this->hasMany(Memory::class, 'person_id');
    }
    public function FamilyEvents()
    {
        return $this->hasMany(FamilyEvent::class, 'person_id');
    }
    public function Favorites()
    {
        return $this->hasMany(Favorite::class, 'person_id');
    }

    public function Scores()
    {
        return $this->hasMany(Score::class, 'score_id');
    }


    // public function Children()
    // {
    //     return $this->hasMany(Person::class, 'id');
    // }
    // public function Children()
    // {
    //     return $this->hasManyThrough(
    //         Person::class,              // Final model we want to access (child Person)
    //         PersonChild::class,         // Intermediate model (PersonChild)
    //         'person_marriage_id',         // Foreign key on PersonChild table
    //         'id',                       // Foreign key on Person table (child's ID)
    //         'id',                       // Local key on PersonMarriage table (parent's ID)
    //         'child_id'                  // Local key on PersonChild table (child's ID)
    //     )->whereHas('PersonMarriages', function($query) {
    //         $query->where('person_id', $this->id)
    //               ->orWhere('spouse_id', $this->id);
    //     })->whereHas('PersonChild', function($query) {
    //         $query->where('person_marriage_id', $this->id);
                 
    //     });
    // }

    // public function Children()
    // {
    //     return $this->hasManyThrough(
    //         Person::class,             // Final model to access (the child Person)
    //         PersonChild::class,        // Intermediate model (PersonChild)
    //         'person_marriage_id',        // Foreign key on PersonChild table
    //         'id',                       // Foreign key on Person table (child's ID)
    //         'id',                       // Local key on PersonMarriage table (parent's ID)
    //         'child_id'                 // Local key on PersonChild table (child's ID)
    //     );
    // }

    public function findRootFatherOfFather()
    {
        // Log current person ID to track recursion
        //Log::info("Checking root ancestor for person ID: " . $this->id);

        // Find any parent marriage where this person is a child
        $parentMarriage = PersonMarriage::whereHas('PersonChild', function ($query) {
            $query->where('child_id', $this->id);
        })->first();

       // Log::info("Parent marriage found for person ID {$this->id}: " . json_encode($parentMarriage));

        // If no parent marriage is found, this person is the root ancestor
        if (!$parentMarriage) {
            //Log::info("Root ancestor found: person ID " . $this->id);
            return $this;  // Return the current person as the root ancestor
        }

        // Identify the parent (using the father if available, otherwise the mother)
        $parentId = $parentMarriage->man_id ? $parentMarriage->man_id : $parentMarriage->woman_id;
        $parent = Person::find($parentId);

        //Log::info("Moving up to parent ID: " . ($parent ? $parent->id : 'null'));

        // Recursive call to move up the lineage
        return $parent ? $parent->findRootFatherOfFather() : $this;
    }
    public function findRootFatherOfMother()
    {
        $parentMarriage = PersonMarriage::whereHas('PersonChild', function ($query) {
            $query->where('child_id', $this->id);
        })->first();

        if (!$parentMarriage) {
            //Log::info("Root ancestor found: person ID " . $this->id);
            return $this;  // Return the current person as the root ancestor
        }

        $parentId = $parentMarriage->woman_id ? $parentMarriage->woman_id : $parentMarriage->man_id;
        $parent = Person::find($parentId);

        return $parent ? $parent->findRootFatherOfFather() : $this;
    }
    public function ancestors()
    {
        // Retrieve parent marriages for this person where they are the child
        $parentMarriages = PersonMarriage::whereHas('PersonChild', function ($query) {
            $query->where('child_id', $this->id);
        })->get();

        $ancestors = collect();
        
        foreach ($parentMarriages as $marriage) {
            if ($marriage->man_id) {
                $father = Person::find($marriage->man_id);
                $ancestors->push($father);
                $ancestors = $ancestors->merge($father->ancestors());
            }
            
            if ($marriage->woman_id) {
                $mother = Person::find($marriage->woman_id);
                $ancestors->push($mother);
                $ancestors = $ancestors->merge($mother->ancestors());
            }
        }

        return $ancestors->unique('id'); // Remove duplicates
    }

     // Recursive ancestry methods
     public function getFullBinaryAncestry($depth = 3)
     {
         //Log::info("Starting ancestry crawl for Person ID: " . $this->id . " with depth: " . $depth);
         return $this->crawlAncestors($this, $depth);
     }
 
    private function crawlAncestors($person, $depth)
    {
        // Base case: stop recursion if depth is zero or person is null
        if ($depth == 0 || !$person) {
            return null;
        }

        //Log::info("Fetching ancestors for Person ID: " . $person->id . " at depth: $depth");

        // Find the parent marriage relations using the PersonChild intermediate model
        $parentMarriage = PersonMarriage::whereHas('PersonChild', function ($query) use ($person) {
            $query->where('child_id', $person->id);
        })->first();

        if (!$parentMarriage) {
            //Log::info("No parent marriage found; Person ID: " . $person->id . " is likely a root ancestor.");
            return [
                'person_id' => $person->id,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'father' => null,
                'mother' => null,
            ];
        }

        // Identify the father and mother from the parent marriage
        $father = Person::find($parentMarriage->man_id);
        $mother = Person::find($parentMarriage->woman_id);

        //Log::info("Person ID: " . $person->id . " -> Father ID: " . ($father ? $father->id : 'null') . ", Mother ID: " . ($mother ? $mother->id : 'null'));

        // Recursive calls for both father and mother, reducing the depth for each level
        $fatherAncestry = $father ? $this->crawlAncestors($father, $depth - 1) : null;
        $motherAncestry = $mother ? $this->crawlAncestors($mother, $depth - 1) : null;

        // Build and return the binary ancestry tree for the current person
        return [
            'person_id' => $person->id,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'father' => [
                'id' => $father ? $father->id : null,
                'name' => $father ? $father->first_name . ' ' . $father->last_name : null,
                'ancestry' => $fatherAncestry  // This continues the recursion on the paternal line
            ],
            'mother' => [
                'id' => $mother ? $mother->id : null,
                'name' => $mother ? $mother->first_name . ' ' . $mother->last_name : null,
                'ancestry' => $motherAncestry  // This continues the recursion on the maternal line
            ]
        ];
    }

    

}

  