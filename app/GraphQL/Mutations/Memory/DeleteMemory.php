<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;

final class DeleteMemory
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
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(Memory::class, AuthAction::Update, $args);

   
        $MemoryResult=Memory::find($args['id']);
        
        if(!$MemoryResult)
        {
            return Error::createLocatedError("Memory-DELETE-RECORD_NOT_FOUND");
        }
        $MemoryResult->editor_id= $this->userId;
        $MemoryResult->save();

        $MemoryResult_filled= $MemoryResult->delete();  
        return $MemoryResult;

        
    }
}