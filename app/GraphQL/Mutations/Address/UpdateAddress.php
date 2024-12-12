<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class UpdateAddress
{
    use AuthUserTrait;
    use AuthorizesMutation;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    protected $userId;

    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveAddress($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
       $this->userAccessibility(Address::class, AuthAction::Delete, $args);

        //args["user_id_creator"]=$user_id;
        $AddressResult=Address::find($args['id']);
        
        if(!$AddressResult)
        {
            return Error::createLocatedError("Address-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $AddressResult_filled= $AddressResult->fill($args);
        $AddressResult->save();       
       
        return $AddressResult;

        
    }
}