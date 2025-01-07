<?php

namespace App\GraphQL\Queries\TalentHeader;

use App\Models\TalentHeader;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetTalentHeaders
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
    function resolveTalentHeader($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $TalentHeaders = TalentHeader::where('deleted_at', null);
        // return $TalentHeaders;

        $query = $this->getModelByAuthorization(TalentHeader::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}