<?php

namespace App\GraphQL\Queries\TalentHeader;

use App\Models\TalentHeader;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetTalentHeader
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
    function resolveTalentHeader($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $TalentHeader = $this->getModelByAuthorization(TalentHeader::class, $args);
        return $TalentHeader->first();
    }
}