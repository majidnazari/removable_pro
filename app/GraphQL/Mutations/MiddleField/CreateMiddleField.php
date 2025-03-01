<?php

namespace App\GraphQL\Mutations\MiddleField;

use App\Models\MiddleField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateMiddleField
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
    public function resolveMiddleField($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    { 

        $this->userId = $this->getUserId();

        $MiddleFieldModel=[
           
            "major_field_id" => $args['major_field_id'],
            "title" => $args['title'],
            "creator_id" =>  $this->userId,   
        ];
       

        $this->checkDuplicate(new MiddleField(), $MiddleFieldModel);
        $MiddleFieldResult=MiddleField::create($MiddleFieldModel);
        return $MiddleFieldResult;
    }
}