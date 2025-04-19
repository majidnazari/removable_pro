<?php

namespace App\GraphQL\Queries\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SmallClanTrait;
use Log;


final class GetAddress
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SmallClanTrait;
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
        // $Address = Address::where('id', $args['id']);       
        // return $Address->first();
//      Log::info("the result of new small clan is :" . json_encode($this->getAllPeopleIdsSmallClan(5)));
       //$this->getAllOwnerIdsSmallClan();
        // $this->getAllUserIdsSmallClan(5);
//       Log::info("the args are " . json_encode($args));
        $address = $this->getModelByAuthorization(Address::class, $args);
        return $address->first();
    }
}