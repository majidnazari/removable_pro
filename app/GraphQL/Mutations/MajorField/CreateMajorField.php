<?php

namespace App\GraphQL\Mutations\MajorField;

use App\Models\MajorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateMajorField
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
    public function resolveMajorField($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        $MajorFieldModel = [

            "title" => $args['title'],
            "creator_id" =>  $this->userId,
            //"status" => $args['status']  ?? status::Active       
        ];
        // $is_exist= MajorField::where($MajorFieldModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("MajorField-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new MajorField(), $MajorFieldModel);
        $MajorFieldResult = MajorField::create($MajorFieldModel);
        return $MajorFieldResult;
    }
}