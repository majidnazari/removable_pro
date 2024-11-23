<?php

namespace App\GraphQL\Mutations\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;


final class UpdateCountry
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
    public function resolveCountry($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        //args["user_id_creator"]=$user_id;
        $CountryResult=Country::find($args['id']);
        
        if(!$CountryResult)
        {
            return Error::createLocatedError("Country-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;

        $CountryResult_filled= $CountryResult->fill($args);
        $CountryResult->save();       
       
        return $CountryResult;

        
    }
}