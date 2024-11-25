<?php

namespace App\GraphQL\Mutations\Area;

use App\Models\Area;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use Log;


final class CreateArea
{
    use AuthUserTrait;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveArea($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        $this->userId = $this->getUserId();

        $AreaResult=[
            "city_id" => $args['city_id'],
            "title" => $args['title'],
            "code" => $args['code']            
        ];
        $is_exist= Area::where('title',$args['title'])->where('code',$args['code'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Area-CREATE-RECORD_IS_EXIST");
         }
        $AreaResult_result=Area::create($AreaResult);
        return $AreaResult_result;
    }
}