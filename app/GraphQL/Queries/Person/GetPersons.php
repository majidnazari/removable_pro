<?php

namespace App\GraphQL\Queries\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;

use Log;

final class GetPersons
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
    function resolvePerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         $this->userId = $this->getUserId();

        // $Persons = Person::where('deleted_at', null);
        // return $Persons;

        $query = $this->getModelByAuthorization(Person::class, $args, true);
        //Log::info("the user id is:".  $this->userId);

        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}