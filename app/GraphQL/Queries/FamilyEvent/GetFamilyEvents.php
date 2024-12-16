<?php

namespace App\GraphQL\Queries\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetFamilyEvents
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
    function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $FamilyEvents = FamilyEvent::where('deleted_at', null);
        // return $FamilyEvents;

        $query = $this->getModelByAuthorization(FamilyEvent::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}