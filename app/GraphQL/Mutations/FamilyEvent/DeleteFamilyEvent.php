<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

final class DeleteFamilyEvent
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
    public function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $FamilyEventResult=FamilyEvent::find($args['id']);
        
        if(!$FamilyEventResult)
        {
            return Error::createLocatedError("FamilyEvent-DELETE-RECORD_NOT_FOUND");
        }
        $FamilyEventResult->editor_id=  $this->userId ;
        $FamilyEventResult->save();

        $FamilyEventResult_filled= $FamilyEventResult->delete();  
        return $FamilyEventResult;

        
    }
}