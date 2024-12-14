<?php
namespace App\GraphQL\Queries\GroupCategoryDetail;

use App\Models\GroupCategoryDetail;
use App\Models\GroupDetail;
use App\Models\Group;
use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthorizesMutation;
use App\Traits\AuthorizesUser;
use App\Traits\AuthUserTrait;

use Log;

class GroupCategoryDetailResolver
{
    //use AuthorizesMutation;
    use AuthUserTrait;
    use AuthorizesUser;
    /**
     * Resolver to fetch all related people.
     *
     * @param  GroupCategoryDetail  $groupCategoryDetail
     * @param  array  $args
     * @param  GraphQLContext  $context
     * @param  ResolveInfo  $resolveInfo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // public function getAllPeople($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     // Retrieve all group details for the specific group
    //     $groupDetails = $root->Group->GroupDetails()->with('Person')->get();

    //     // Initialize an empty collection to collect all people
    //     $allPeople = collect();

    //     // Iterate over all GroupDetails to aggregate People
    //     foreach ($groupDetails as $groupDetail) {
    //         // Check if the group detail has an associated Person
    //         if ($groupDetail->Person) {
    //             // Add the person to the collection if not already included
    //             $allPeople->push($groupDetail->Person);
    //         }
    //     }

    //     // Now, remove duplicates by 'id' to ensure no repetitions of the same person
    //     $allPeople = $allPeople->unique('id');

    //     // Log the result of all people combined
    //     Log::info("The all people is: " . json_encode($allPeople));

    //     return $allPeople;
    // }


    public function getAllPeopleFromCategories($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

       //$this->userAccessibility(GroupCategoryDetail::class, AuthAction::Delete, $args);
        // Step 1: Retrieve all group_ids from the GroupCategoryDetails
        $query = $this->getModelByAuthorization(GroupCategoryDetail::class, $args, true);
        $groupCategoryDetails =  $query->select('group_id')->get();

        // Log group IDs to ensure they're retrieved correctly
        Log::info("Group IDs from GroupCategoryDetails: " . json_encode($groupCategoryDetails));

        // Initialize an empty collection to hold all people
        $allPeople = collect();

        // Step 2: Loop through each group_id and find the corresponding groups
        foreach ($groupCategoryDetails as $categoryDetail) {
            // Fetch the group by the group_id
            $group = Group::with('GroupDetails.Person')->find($categoryDetail->group_id);

            if ($group) {
                // Log the group details with people
                Log::info("Found group: " . json_encode($group));

                // Step 3: Loop through the group details and add the associated people
                foreach ($group->GroupDetails as $groupDetail) {
                    if ($groupDetail->Person) {
                        // Add the people to the collection
                        $allPeople->push($groupDetail->Person);
                    }
                }
            } else {
                Log::info("Group with ID " . $categoryDetail->group_id . " not found.");
            }
        }

        // Remove duplicates by person ID
        $allPeople = $allPeople->unique('id');

        // Log final list of all people
        Log::info("Final list of all people: " . json_encode($allPeople));

        return $allPeople;
    }

}
