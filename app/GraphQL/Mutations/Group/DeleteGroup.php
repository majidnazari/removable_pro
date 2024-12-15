<?php

namespace App\GraphQL\Mutations\Group;

use App\Models\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class DeleteGroup
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
    public function resolveGroup($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        
        $this->userId = $this->getUserId();
       $this->userAccessibility(Group::class, AuthAction::Delete, $args);

    
        $GroupResult=Group::find($args['id']);
        
        if(!$GroupResult)
        {
            return Error::createLocatedError("Group-DELETE-RECORD_NOT_FOUND");
        }

        $GroupResult->editor_id=  $this->userId;
        $GroupResult->save();

        $GroupResult_filled= $GroupResult->delete();  
        return $GroupResult;

        
    }
}