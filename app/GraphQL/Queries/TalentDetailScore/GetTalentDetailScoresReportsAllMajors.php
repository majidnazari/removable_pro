<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use App\Models\MajorField;
use Log;


final class GetTalentDetailScoresReportsAllMajors
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
    function resolveTalentDetailScoreReportsAllMajors($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        // Query builder for paginated results
        $query = TalentDetailScore::selectRaw('
                major_fields.id as major_field_id,
                major_fields.title as major_field_title,
                ROUND(AVG(talent_detail_scores.score), 2) as average_score
            ')
            ->join('talent_details', 'talent_detail_scores.talent_detail_id', '=', 'talent_details.id')
            ->join('minor_fields', 'talent_details.minor_field_id', '=', 'minor_fields.id')
            ->join('middle_fields', 'minor_fields.middle_field_id', '=', 'middle_fields.id')
            ->join('major_fields', 'middle_fields.major_field_id', '=', 'major_fields.id')
            ->whereNull('talent_detail_scores.deleted_at')
            ->where('talent_details.creator_id', $this->userId)
            ->groupBy('major_fields.id', 'major_fields.title');

//           Log::info("the major log is :" . $query->ToSql());

        return $query; // Return query builder for pagination

        // $this->userId = $this->getUserId();

        // $majorField = MajorField::with('MiddleFields.MinorFields.TalentDetails.TalentDetailScores')->find(1);
        // // $minorFields = $majorField->MiddleFields->flatMap->MinorFields;


//       Log::info(" the result major is :" .
//           json_encode($majorField));

    }
}