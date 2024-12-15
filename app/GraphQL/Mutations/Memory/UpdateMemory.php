<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;
use Log;


final class UpdateMemory
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
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
        $MemoryResult = Memory::find($args['id']);

        if (!$MemoryResult) {
            return Error::createLocatedError("Memory-UPDATE-RECORD_NOT_FOUND");
        }

        //Log::info("the memory is:" . json_encode($MemoryResult));
       // $this->checkDuplicate(new Memory(), $MemoryResult->toArray());

        // Ignore 'created_at' and 'updated_at' columns
        $this->checkDuplicate(
            new Memory(),
            $args,
            ['id','editor_id','created_at', 'updated_at'],
            $args['id']
        );


        $args['editor_id'] = $this->userId;
        $MemoryResult_filled = $MemoryResult->fill($args);
        $MemoryResult->save();

        return $MemoryResult;


    }
}