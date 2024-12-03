<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class DeleteCity
{
    use AuthUserTrait;
    use checkMutationAuthorization;
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
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(City::class, AuthAction::Delete, $args);

  
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