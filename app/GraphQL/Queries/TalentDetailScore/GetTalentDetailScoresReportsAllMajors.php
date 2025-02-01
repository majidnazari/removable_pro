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
                AVG(talent_detail_scores.score) as average_score
            ')
            ->join('talent_details', 'talent_detail_scores.talent_detail_id', '=', 'talent_details.id')
            ->join('minor_fields', 'talent_details.minor_field_id', '=', 'minor_fields.id')
            ->join('middle_fields', 'minor_fields.middle_field_id', '=', 'middle_fields.id')
            ->join('major_fields', 'middle_fields.major_field_id', '=', 'major_fields.id')
            ->whereNull('talent_detail_scores.deleted_at')
            ->where('talent_details.creator_id', $this->userId)
            ->groupBy('major_fields.id', 'major_fields.title');

            //Log::info("the major log is :" . $query->ToSql());

        return $query; // Return query builder for pagination

        // $this->userId = $this->getUserId();

        // $majorField = MajorField::with('MiddleFields.MinorFields.TalentDetails.TalentDetailScores')->find(1);
        // // $minorFields = $majorField->MiddleFields->flatMap->MinorFields;


        // Log::info(" the result major is :" .
        //     json_encode($majorField));


        // //return null;
        // $query = MajorField::select(['id as major_field_id', 'title as major_field_title'])
        //     ->with([
        //         'MiddleFields111' => function ($query) {
        //             $query->select(['id', 'major_field_id', 'title']);
        //         },
        //         'MiddleFields.MinorFields' => function ($query) {
        //             $query->select(['id', 'middle_field_id', 'title']);
        //         },
        //         'MiddleFields.MinorFields.TalentDetails' => function ($query) {
        //             $query->select(['id', 'minor_field_id']);
        //         },
        //         'MiddleFields.MinorFields.TalentDetails.TalentDetailScores1' => function ($query) {
        //             $query->select(['id', 'talent_detail_id', 'score'])
        //                 ->whereNull('deleted_at1');
        //         }
        //     ])
        //     //->withAvg('MiddleFields.MinorFields.TalentDetails.TalentDetailScores', 'score')
        //     ->whereHas('MiddleFields.MinorFields.TalentDetails', function ($query) {
        //         $query->where('creator_id', $this->userId);
        //     });

        // return $query;


    }
}