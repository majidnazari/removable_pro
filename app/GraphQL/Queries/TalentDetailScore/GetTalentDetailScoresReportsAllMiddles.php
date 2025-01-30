<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;


final class GetTalentDetailScoresReportsAllMiddles
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
    function resolveTalentDetailScoreReportsAllMiddles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $query = TalentDetailScore::selectRaw('
            middle_fields.id as middle_field_id,
            middle_fields.title as middle_field_title,
            AVG(talent_detail_scores.score) as average_score,
            major_fields.id as major_field_id,
            major_fields.title as major_field_title
        ')
            ->join('talent_details', 'talent_detail_scores.talent_detail_id', '=', 'talent_details.id')
            ->join('minor_fields', 'talent_details.minor_field_id', '=', 'minor_fields.id')
            ->join('middle_fields', 'minor_fields.middle_field_id', '=', 'middle_fields.id')
            ->join('major_fields', 'middle_fields.major_field_id', '=', 'major_fields.id')
            ->whereNull('talent_detail_scores.deleted_at')
            ->where('talent_details.creator_id', $this->userId);

        if (isset($args['major_field_id'])) {
            $query->where('major_fields.id', $args['major_field_id']);
        }

        $query->groupBy('middle_fields.id', 'middle_fields.title', 'major_fields.id', 'major_fields.title');

        return $query; // Return query builder for pagination
    }
}