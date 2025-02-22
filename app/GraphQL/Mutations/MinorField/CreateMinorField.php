<?php

namespace App\GraphQL\Mutations\MinorField;

use App\Models\MinorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateMinorField
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
    public function resolveMinorField($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    { 

        $this->userId = $this->getUserId();

        $MinorFieldModel=[
          
            "middle_field_id" => $args['middle_field_id'],
            "title" => $args['title'],
            "creator_id" =>  $this->userId,
                 
        ];
        // $is_exist= MinorField::where($MinorFieldModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("MinorField-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new MinorField(), $MinorFieldModel);
        $MinorFieldResult=MinorField::create($MinorFieldModel);
        return $MinorFieldResult;
    }
}