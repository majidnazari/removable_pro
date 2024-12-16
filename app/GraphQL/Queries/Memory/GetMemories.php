<?php

namespace App\GraphQL\Queries\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\GetsPeopleInGroups;


final class GetMemories
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    use GetsPeopleInGroups;
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
        // $this->userId = $this->getUserId();

        // $memories = Memory::where('deleted_at', null);
        // return $memories;

        $query = $this->getModelByAuthorization(Memory::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);

        // Use the canAccessMemory method to filter based on access rights
        // $query = $query->get()->filter(function ($memory) {
        //     return $this->canAccessMemory($memory);
        // });

       
        return  $query;
    }
}