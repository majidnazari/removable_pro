<?php

namespace App\GraphQL\Queries\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Models\User;

use Illuminate\Support\Facades\Gate;
use Log;

final class GetUserMergeRequests
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        // Get UserMergeRequest model based on user authorization
        $query = $this->getModelByAuthorization(UserMergeRequest::class, $args, true);
        // Apply search filters and ordering
        $query = $this->applySearchFilters($query, $args);
        return $query;
    }
}