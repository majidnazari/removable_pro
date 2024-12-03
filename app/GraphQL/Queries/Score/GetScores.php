<?php

namespace App\GraphQL\Queries\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;

use Log;

final class GetScores
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
    function resolveScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $Scores = Score::where('deleted_at', null);

        // //log::info("the Scores is:" . json_encode($Scores));
        // return $Scores;
        $query = $this->getModelByAuthorization(Score::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}