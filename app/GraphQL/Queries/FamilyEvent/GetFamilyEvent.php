<?php

namespace App\GraphQL\Queries\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetFamilyEvent
{
    use AuthUserTrait;
    use AuthorizesUser;

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
       
        $FamilyEvent = $this->getModelByAuthorization(FamilyEvent::class, $args);
        return $FamilyEvent->first();
    }
}