<?php

namespace App\GraphQL\Mutations\TalentDetail;

use App\Models\TalentDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateTalentDetail
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
    public function resolveTalentDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 

        $this->userId = $this->getUserId();

        $TalentDetailModel=[
            "creator_id" =>  $this->userId,
            "talent_header_id" => $args['talent_header_id'],
            "minor_field_id" => $args['minor_field_id'],
            "status" => $args['status']  ?? status::Active       
        ];
        // $is_exist= TalentDetail::where($TalentDetailModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("TalentDetail-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new TalentDetail(), $TalentDetailModel);
        $TalentDetailResult=TalentDetail::create($TalentDetailModel);
        return $TalentDetailResult;
    }
}