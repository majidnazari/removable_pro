<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteCity
{
    protected $userId;

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
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;    
        $CityResult=City::find($args['id']);
        
        if(!$CityResult)
        {
            return Error::createLocatedError("City-DELETE-RECORD_NOT_FOUND");
        }

        $CityResult->editor_id= $this->userId;
        $CityResult->save();


        $CityResult_filled= $CityResult->delete();  
        return $CityResult;

        
    }
}