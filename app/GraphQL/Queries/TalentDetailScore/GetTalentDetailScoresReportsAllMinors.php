<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;


final class GetTalentDetailScoresReportsAllMinors
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
    function resolveTalentDetailScoreReportsAllMinors($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $query = TalentDetailScore::selectRaw('
       
        minor_fields.id as minor_field_id,
        minor_fields.title as minor_field_title,
        ROUND(AVG(talent_detail_scores.score), 2) as average_score,
        middle_fields.id as middle_field_id,
        middle_fields.title as middle_field_title
    ')
            ->join('talent_details', 'talent_detail_scores.talent_detail_id', '=', 'talent_details.id')
            ->join('minor_fields', 'talent_details.minor_field_id', '=', 'minor_fields.id')
            ->join('middle_fields', 'minor_fields.middle_field_id', '=', 'middle_fields.id')
            ->whereNull('talent_detail_scores.deleted_at')
            ->where('talent_details.creator_id', $this->userId);

        //  Filter by middle field if provided
        if (isset($args['middle_field_id'])) {
            $query->where('middle_fields.id', $args['middle_field_id']);
        }

        // Group by talent header and minor field
        $query->groupBy(
            
            'minor_fields.id',
            'minor_fields.title',
            'middle_fields.id',
            'middle_fields.title'
        );

//      Log::info("the sql is :" . $query->toSql());

        return $query; // Return query builder for 
    }
}