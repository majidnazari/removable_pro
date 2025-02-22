<?php

namespace App\GraphQL\Mutations\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;

final class DeleteCountry
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCountry($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();

        try {

            $CountryResult = $this->userAccessibility(Country::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($CountryResult, $args, $this->userId);
    //    $this->userAccessibility(Country::class, AuthAction::Delete, $args);

 
    //     $CountryResult=Country::find($args['id']);
        
    //     if(!$CountryResult)
    //     {
    //         return Error::createLocatedError("Country-DELETE-RECORD_NOT_FOUND");
    //     }
    //     //$args['editor_id']=$user_id;

    //     $CountryResult->editor_id=  $this->userId ;
    //     $CountryResult->save();

    //     $CountryResult_filled= $CountryResult->delete();  
    //     return $CountryResult;

        
    }
}