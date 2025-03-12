<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\GraphQL\Enums\UserStatus;

use Log;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $node_code
 * @property int $node_level_x
 * @property int $node_level_y
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $birth_date
 * @property string|null $death_date
 * @property int $is_owner
 * @property int $gender 1 is man 0 is woman
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $Addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyEvent> $FamilyEvents
 * @property-read int|null $family_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $Favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $Memories
 * @property-read int|null $memories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriages
 * @property-read int|null $person_marriages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $Scores
 * @property-read int|null $scores_count
 * @method static \Database\Factories\PersonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 
 * @property string|null $country_code
 * @property string|null $mobile
 * @mixin \Eloquent
 */
class Person extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'node_code',
        'first_name',
        'last_name',
        'birth_date',
        'death_date',
        'mobile',
        'is_owner',
        'gender',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;
    private $rootAncestors = [];

    public const TABLE_NAME = 'people';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';
    public const COLUMN_CHILD_ID = 'child_id';
    public const COLUMN_ADDRESS_ID = 'address_id';
    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_MAN_ID = 'man_id';
    public const COLUMN_WOMAN_ID = 'woman_id';
    public const COLUMN_PERSONCHILD = 'PersonChild';
    public const COLUMN_SCORE_ID = 'score_id';



    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }


    public function PersonMarriages()
    {
        // return $this->hasMany(PersonMarriage::class, 'person_id')->orwhere('spouse_id',$this->id);
        return $this->hasMany(PersonMarriage::class, self::COLUMN_MAN_ID)->orWhere(self::COLUMN_WOMAN_ID, $this->id);

    }

    public function Addresses()
    {
        return $this->hasMany(Address::class, self::COLUMN_PERSON_ID);
    }

    public function Memories()
    {
        return $this->hasMany(Memory::class, self::COLUMN_PERSON_ID);
    }
    public function FamilyEvents()
    {
        return $this->hasMany(FamilyEvent::class, self::COLUMN_PERSON_ID);
    }
    public function Favorites()
    {
        return $this->hasMany(Favorite::class, self::COLUMN_PERSON_ID);
    }

    public function Scores()
    {
        return $this->hasMany(Score::class, self::COLUMN_SCORE_ID);
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
        $parentMarriage = PersonMarriage::whereHas(self::COLUMN_PERSONCHILD, function ($query) {
            $query->where(self::COLUMN_CHILD_ID, $this->id);
        })->first();

        //Log::info("Parent marriage found for person ID {$this->id}: " . json_encode($parentMarriage));

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
        $parentMarriage = PersonMarriage::whereHas(self::COLUMN_PERSONCHILD, function ($query) {
            $query->where(self::COLUMN_CHILD_ID, $this->id);
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
        $parentMarriages = PersonMarriage::whereHas(self::COLUMN_PERSONCHILD, function ($query) {
            $query->where(self::COLUMN_CHILD_ID, $this->id);
        })->get();

        //Log::info("ancestry method is :" . json_encode($parentMarriages));

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

        return $ancestors->unique(self::COLUMN_ID); // Remove duplicates
    }

    // Recursive ancestry methods
    public function getFullBinaryAncestry($depth = 3)
    {
        //Log::info("Starting ancestry crawl for Person ID: " . $this->id . " with depth: " . $depth);
        // return $this->crawlAncestors($this, $depth);

        $result = $this->crawlAncestors($this, $depth);

        // Map $this->rootAncestors to extract only id, first_name, and last_name
        // $heads = array_map(function ($ancestor) {
        //     return [
        //         'id' => $ancestor['person_id'],
        //         'first_name' => $ancestor['first_name'],
        //         'last_name' => $ancestor['last_name']
        //     ];
        // }, $this->rootAncestors);

        // Add 'order' field to each ancestor in $this->rootAncestors
        foreach ($this->rootAncestors as $index => &$ancestor) {
            $ancestor['order'] = $index + 1; // Add order starting from 1
        }

        // Log the simplified heads array
        //Log::info("All top-level ancestors: " . json_encode($this->rootAncestors));

        return [$result, $this->rootAncestors];
    }

    public function getFullBinaryAncestryheads($depth = 3)
    {
        //Log::info("Starting ancestry crawl for Person ID: " . $this->id . " with depth: " . $depth);
        // return $this->crawlAncestors($this, $depth);

        $result = $this->crawlAncestors($this, $depth);

        // Map $this->rootAncestors to extract only id, first_name, and last_name
        // $heads = array_map(function ($ancestor) {
        //     return [
        //         'id' => $ancestor['person_id'],
        //         'first_name' => $ancestor['first_name'],
        //         'last_name' => $ancestor['last_name']
        //     ];
        // }, $this->rootAncestors);

        // Add 'order' field to each ancestor in $this->rootAncestors
        foreach ($this->rootAncestors as $index => &$ancestor) {
            $ancestor['order'] = $index + 1; // Add order starting from 1
        }

        // Log the simplified heads array
        //Log::info("All top-level ancestors: " . json_encode($this->rootAncestors));

        return  $this->rootAncestors;
    }
    private function crawlAncestors($person, $depth)
    {
        // Base case: stop recursion if depth is zero or person is null
        if ($depth == 0 || !$person) {
            return null;
        }

        //Log::info("Fetching ancestors for Person ID: " . $person->id . " at depth: $depth");

        // Find the parent marriage relations using the PersonChild intermediate model
        $parentMarriage = PersonMarriage::whereHas(self::COLUMN_PERSONCHILD, function ($query) use ($person) {
            $query->where(self::COLUMN_CHILD_ID, $person->id);
        })->first();

        if (!$parentMarriage) {
            //Log::info("No parent marriage found; Person ID: " . $person->id . " is likely a root ancestor.");
            $rootAncestor = [
                'person_id' => $person->id,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'father' => null,
                'mother' => null,
            ];

            // Add this ancestor to the rootAncestors array
            $this->rootAncestors[] = $rootAncestor;

            return $rootAncestor;

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
            ],
            //'heade' => $this->rootAncestors
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::Active);
    }



    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}

