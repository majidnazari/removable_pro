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
use App\Models\TalentDetail;
use App\Models\TalentHeader;
use App\Models\GroupDetail;
use Exception;
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

         // Fetch the TalentDetail associated with the given talent_detail_id
         $talentDetail = TalentDetail::find($args['talent_detail_id']);

         if (!$talentDetail) {
            throw new Error("TalentDetail not found.");
         }
 
         // Check if the creator_id is the same as the logged-in user
         if ($talentDetail->creator_id == $this->userId) {
            throw new Error("You cannot score your own talent.");
         }

         // Find the related TalentHeader
        $talentHeader = TalentHeader::find($talentDetail->talent_header_id);

        if (!$talentHeader) {
            throw new Error('TalentHeader not found.');
        }

        // Get the group_category_id from TalentHeader
        $groupCategoryId = $talentHeader->group_category_id;

        // Check if the user is part of any group in the related group category
        $isUserInGroup = GroupDetail::whereHas('Groups', function ($query) use ($groupCategoryId) {
            $query->where('group_category_id', $groupCategoryId);
        })->whereHas('users', function ($query) {
            $query->where('user_id', $this->userId);
        })->exists();

        Log::info("the users all are:" . json_encode( $isUserInGroup));

        if (!$isUserInGroup) {
            throw new Error('You must be a part of the associated group to create a talent detail score.');
        }



        $TalentDetailScoreModel=[
            "creator_id" =>  $this->userId,
            "participating_user_id" => $this->userId,//$args['participating_user_id'],

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