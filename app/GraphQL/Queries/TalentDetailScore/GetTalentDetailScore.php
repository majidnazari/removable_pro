<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetTalentDetailScore
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
    function resolveTalentDetailScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $TalentDetailScore = $this->getModelByAuthorization(TalentDetailScore::class, $args);
        return $TalentDetailScore->first();
    }
}