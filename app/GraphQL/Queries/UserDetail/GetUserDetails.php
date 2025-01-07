<?php

namespace App\GraphQL\Queries\UserDetail;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;

use Log;

final class GetUserDetails
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
    function resolveUserDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $UserDetails = UserDetail::where('deleted_at', null);

        // //log::info("the UserDetails is:" . json_encode($UserDetails));
        // return $UserDetails;
        $query = $this->getModelByAuthorization(UserDetail::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}