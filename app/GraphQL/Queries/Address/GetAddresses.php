<?php

namespace App\GraphQL\Queries\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetAddresses
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;

    protected $userId;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveAddress($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();
        // $Addresss = Address::where('deleted_at', null)->with("City");
        // return $Addresss;

        $query = $this->getModelByAuthorization(Address::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}