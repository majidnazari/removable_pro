<?php

namespace App\GraphQL\Mutations\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\TalentScore;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateTalentDetailScore
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveTalentDetailScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 

        $this->userId = $this->getUserId();

        $TalentDetailScoreModel=[
            "creator_id" =>  $this->userId,
            "participating_user_id" => $args['participating_user_id'],

            "talent_detail_id" => $args['talent_detail_id'],
            "score" => $args['score'] ?? TalentScore::None,
           
            "status" => $args['status']  ?? status::Active       
        ];
        // $is_exist= TalentDetailScore::where($TalentDetailScoreModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("TalentDetailScore-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new TalentDetailScore(), $TalentDetailScoreModel);
        $TalentDetailScoreResult=TalentDetailScore::create($TalentDetailScoreModel);
        return $TalentDetailScoreResult;
    }
}