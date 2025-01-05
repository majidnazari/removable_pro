<?php

namespace App\GraphQL\Mutations\MicroField;

use App\Models\MicroField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateMicroField
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
    public function resolveMicroField($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 

        $this->userId = $this->getUserId();

        $MicroFieldModel=[
          
            "middle_field_id" => $args['middle_field_id'],
            "title" => $args['title'],
                 
        ];
        // $is_exist= MicroField::where($MicroFieldModel)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("MicroField-CREATE-RECORD_IS_EXIST");
        //  }

        $this->checkDuplicate(new MicroField(), $MicroFieldModel);
        $MicroFieldResult=MicroField::create($MicroFieldModel);
        return $MicroFieldResult;
    }
}