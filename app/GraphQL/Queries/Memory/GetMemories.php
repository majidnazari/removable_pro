<?php

namespace App\GraphQL\Queries\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Traits\GetsPeopleInGroups;

use Log;


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

        Log::info("the before filter is :" . json_encode($query->get()));

        //Use the canAccessMemory method to filter based on access rights
        $query->get()->filter(function ($memory) {
            $result= $this->canAccessMemory($memory);

            Log::info("the result of final result is:". json_encode($result));
        });

       
        return  $query;
    }
}