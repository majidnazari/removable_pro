<?php

namespace App\GraphQL\Queries\Memory;

use App\Models\Memory;
use App\Models\GroupDetail;
use App\Models\GroupCategoryDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\GetsPeopleInGroups;
use App\Traits\MergeRequestTrait;

use Log;


final class GetMemories
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    use GetsPeopleInGroups;
    protected $userId;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveMemory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
       
        // $result = null;
        // $this->userId = $this->getUserId();

        // $query = Memory::where('deleted_at', null)
        //     ->where('creator_id', $this->userId)
        //     ->orWhere('creator_id', $this->userId);
        // return $memories;

        $query = $this->getModelByAuthorization(Memory::class, $args, true);
        $query = $this->applySearchFilters($query, $args);

        return $query;
        //Log::info("Query before filtering: " . json_encode($query->toSql()));

        // Apply the user access check during the query build
        //$query->whereHas('GroupCategory.GroupCategoryDetails', function ($query) {
        //  Log::info("each memory : " . json_encode($query));

        // Check if the user is related to the group via the GroupCategoryDetail
        //$query->whereIn('group_category_id', $this->getUserGroupCategoryIds());
        // });

        //Log::info("Query after filtering: " . $query->toSql());
        // Log::info("Final query parameters: " . json_encode($query->getBindings()));
        // Return the query builder (without fetching the data yet)

        // //Use the canAccessMemory method to filter based on access rights
        // $query->get()->filter(function ($memory) {
        //     //$result= $this->canAccessMemory($memory);
        //     Log::info("each memory  must check exist in group or not : " . json_encode($memory));

        //     // Fetch the group category details associated with the memory's group category ID
        //     $groupCategoryDetails = GroupCategoryDetail::where('group_category_id', $memory->group_category_id)->get();

        //     Log::info("get memory groupCategorydetails according group category : ".json_encode($groupCategoryDetails));


        //     if ($groupCategoryDetails->isEmpty()) {
        //         // If no group category details are found, deny access
        //         Log::info("No groupCategoryDetails found for memory, access denied.");
        //         return false;
        //     }

        //     // Retrieve all group_ids associated with the group category details
        //     $groupIds = $groupCategoryDetails->pluck('group_id')->toArray();

        //         Log::info("No groupIds ".json_encode($groupIds));

        //     // Fetch the related person_ids from the GroupDetails table for all the group_ids
        //     $userIds = GroupDetail::whereIn('group_id', $groupIds)
        //         ->pluck('user_id')  // Assuming person_id is the column storing persons related to each group
        //         ->toArray();

        //     // Check if the current user is in the list of persons in the related groups
        //     $hasAccess = in_array($this->userId, $userIds);

        //     // Log if access is granted or denied for this memory
        //     Log::info("Memory access check result: " . ($hasAccess ? 'Access Granted' : 'Access Denied'));

        //     return $hasAccess;
        // });
        // return $query;


        //return $query;
    }

    private function getUserGroupCategoryIds()
    {
        // Get the user’s groups via GroupCategoryDetail
        $user = $this->getUser();

        // Log::info("the user id is :" . $user->id);
        $groupCategoryIds = GroupCategoryDetail::whereHas('Group', function ($query) use ($user) {
            // Check if the user is associated with the group through GroupDetail
            $query->whereHas('GroupDetails', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })->pluck('group_category_id')->toArray();

        // Log::info("User group category IDs: " . json_encode($groupCategoryIds));

        return $groupCategoryIds;
    }

}