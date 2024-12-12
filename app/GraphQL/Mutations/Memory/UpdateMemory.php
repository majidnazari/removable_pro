<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;


final class UpdateMemory
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
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
       $this->userAccessibility(Memory::class, AuthAction::Update, $args);


        //args["user_id_creator"]=$user_id;
        $MemoryResult=Memory::find($args['id']);
        
        if(!$MemoryResult)
        {
            return Error::createLocatedError("Memory-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$this->userId;
        $MemoryResult_filled= $MemoryResult->fill($args);
        $MemoryResult->save();       
       
        return $MemoryResult;

        
    }
}