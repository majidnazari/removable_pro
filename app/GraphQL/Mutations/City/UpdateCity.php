<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class UpdateCity
{
   use AuthUserTrait;
   use checkMutationAuthorization;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCity($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(City::class, AuthAction::Delete, $args);


        //args["user_id_creator"]=$user_id;
        $CityResult=City::find($args['id']);
        
        if(!$CityResult)
        {
            return Error::createLocatedError("City-UPDATE-RECORD_NOT_FOUND");
        }
        $CityResult_filled= $CityResult->fill($args);
        $CityResult->save();       
       
        return $CityResult;

        
    }
}