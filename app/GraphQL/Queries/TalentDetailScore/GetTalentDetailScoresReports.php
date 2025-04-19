<?php

namespace App\GraphQL\Queries\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;
use Log;


final class GetTalentDetailScoresReports
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
    function resolveTalentDetailScoreReportsMajor($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $TalentDetailScores = TalentDetailScore::whereNull('deleted_at')
            ->whereHas('TalentDetail', function ($query) {
                $query->where('creator_id', $this->userId);
            })
            ->with('TalentDetail');

        if (isset($args['talent_header_id'])) {
            $TalentDetailScores = TalentDetailScore::whereNull('deleted_at')
                ->whereHas('TalentDetail', function ($query) use ($args) {
                    $query->where('talent_header_id', $args['talent_header_id']);
                })
                ->with('TalentDetail');
        }

        if (isset($args['scoreEqual'])) {

            $TalentDetailScores = $TalentDetailScores->where("score", (int) $args['scoreEqual']);
        }
        if (isset($args['scoreEqualOrMore'])) {

            $TalentDetailScores = $TalentDetailScores->where("score", ">=", (int) $args['scoreEqualOrMore']);
        }
        if (isset($args['scoreEqualOrLess'])) {

            $TalentDetailScores = $TalentDetailScores->where("score", "<=", (int) $args['scoreEqualOrLess']);
        }
        if (isset($args['status'])) {

            $TalentDetailScores = $TalentDetailScores->where("score", (int) $args['status']);
        }
        if (isset($args['minor_field_id'])) {

            $TalentDetailScores = $TalentDetailScores
                ->whereHas('TalentDetail', function ($query) use ($args) {
                    $query->whereHas('MinorField', function ($queryMinor) use ($args) {
                        $queryMinor->where('id', $args['minor_field_id']);
                    })
                        ->with('TalentDetail.MinorField');
                })
                ->with('TalentDetail');
        }

        if (isset($args['middle_field_id'])) {

            $TalentDetailScores = $TalentDetailScores
                ->whereHas('TalentDetail', function ($query) use ($args) {
                    $query->whereHas('MinorField', function ($queryMinor) use ($args) {
                        $queryMinor->whereHas('id', $args['middle_field_id'], function ($queryMiddle) use ($args) {
                            $queryMiddle->where('Middle', $args['middle_field_id']);
                        })->with('TalentDetail.MinorField.MiddleField');
                    })->with('TalentDetail.MinorField');
                })->with('TalentDetail');

        }
        if (isset($args['major_field_id'])) {
            $TalentDetailScores = $TalentDetailScores
                ->whereHas('TalentDetail', function ($query) use ($args) {
                    $query->whereHas('MinorField', function ($queryMinor) use ($args) {

                        $queryMinor->whereHas('MiddleField', function ($queryMiddle) use ($args) {
                            $queryMiddle->whereHas('MajorField', function ($queryMajor) use ($args) {
                                $queryMajor->where('id', $args['major_field_id']);

                            })->with('TalentDetail.MinorField.MiddleField.MajorField');
                        })->with('TalentDetail.MinorField.MiddleField');
                    })
                        ->with('TalentDetail.MinorField');
                })
                ->with('TalentDetail');
        }
        //->get(); // Fetch results
        return $TalentDetailScores;

    }
}