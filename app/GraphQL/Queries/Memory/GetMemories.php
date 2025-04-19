<?php

namespace App\GraphQL\Queries\Memory;

use App\GraphQL\Enums\ConfirmMemoryStatus;
use App\GraphQL\Enums\Status;
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
                $extraConditions = [
                        ['column' => 'confirm_status', 'operator' => '=', 'value' => ConfirmMemoryStatus::Accept->value],
                        ['column' => 'status', 'operator' => '=', 'value' => Status::Active->value],
                ];

                $query = $this->getModelByAuthorization(Memory::class, $args, true);
                $query = $this->applySearchFilters($query, $args, $extraConditions);

                return $query;

        }

        private function getUserGroupCategoryIds()
        {
                // Get the user’s groups via GroupCategoryDetail
                $user = $this->getUser();

                $groupCategoryIds = GroupCategoryDetail::whereHas('Group', function ($query) use ($user) {
                        // Check if the user is associated with the group through GroupDetail
                        $query->whereHas('GroupDetails', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                        });
                })->pluck('group_category_id')->toArray();

                return $groupCategoryIds;
        }

}