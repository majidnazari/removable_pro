<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;
final class DeleteProvince
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $ProvinceResult=Province::find($args['id']);
        
        if(!$ProvinceResult)
        {
            return Error::createLocatedError("Province-DELETE-RECORD_NOT_FOUND");
        }

        $ProvinceResult->editor_id= $this->userId;
        $ProvinceResult->save(); 


        $ProvinceResult_filled= $ProvinceResult->delete();  
        return $ProvinceResult;

        
    }
}