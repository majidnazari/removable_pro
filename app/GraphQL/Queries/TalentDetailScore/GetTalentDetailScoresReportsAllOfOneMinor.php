<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;


final class GetTalentDetailScoresReportsAllOfOneMinor
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
    function resolveTalentDetailScoreReportsAllOfOneMinor($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        // Ensure the minor_field_id is provided in the arguments
        if (!isset($args['minor_field_id'])) {
            throw new \Exception("minor_field_id is required");
        }
    
        $query = TalentDetailScore::selectRaw('
            talent_details.talent_header_id,
            minor_fields.id as minor_field_id,
            minor_fields.title as minor_field_title,
            AVG(talent_detail_scores.score) as average_score,
            middle_fields.id as middle_field_id,
            middle_fields.title as middle_field_title
        ')
            ->join('talent_details', 'talent_detail_scores.talent_detail_id', '=', 'talent_details.id')
            ->join('minor_fields', 'talent_details.minor_field_id', '=', 'minor_fields.id')
            ->join('middle_fields', 'minor_fields.middle_field_id', '=', 'middle_fields.id')
            ->whereNull('talent_detail_scores.deleted_at')
            ->where('talent_details.creator_id', $this->userId)
            // ✅ Filter by the specific minor_field_id
            ->where('minor_fields.id', $args['minor_field_id']);
    
        // ✅ Group by talent_header_id and middle fields to get unique records for each minor field
        $query->groupBy(
            'talent_details.talent_header_id',
            'minor_fields.id',
            'minor_fields.title',
            'middle_fields.id',
            'middle_fields.title'
        );
    
        // Log::info("the sql is: " . $query->toSql());
    
        return $query; // Return query builder for pagination
    }
}