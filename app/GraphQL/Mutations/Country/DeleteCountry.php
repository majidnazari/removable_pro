<?php

namespace App\GraphQL\Mutations\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;

final class DeleteCountry
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
        
        $this->userId = $this->getUserId();
       $this->userAccessibility(Country::class, AuthAction::Delete, $args);

 
        $CountryResult=Country::find($args['id']);
        
        if(!$CountryResult)
        {
            return Error::createLocatedError("Country-DELETE-RECORD_NOT_FOUND");
        }
        //$args['editor_id']=$user_id;

        $CountryResult->editor_id=  $this->userId ;
        $CountryResult->save();

        $CountryResult_filled= $CountryResult->delete();  
        return $CountryResult;

        
    }
}