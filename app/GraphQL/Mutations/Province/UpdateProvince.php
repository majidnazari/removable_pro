<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;


use Exception;

final class UpdateProvince
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
        //args["user_id_creator"]=$this->userId;
        $ProvinceResult=Province::find($args['id']);
        
        if(!$ProvinceResult)
        {
            return Error::createLocatedError("Province-UPDATE-RECORD_NOT_FOUND");
        }

        $args['editor_id']=$this->userId;
        
        $ProvinceResult_filled= $ProvinceResult->fill($args);
        $ProvinceResult->save();       
       
        return $ProvinceResult;

        
    }
}