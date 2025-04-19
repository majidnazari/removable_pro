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
use App\Traits\FindOwnerTrait;
use App\Exceptions\CustomValidationException;

use Log;


final class UpdateSuspendMemory
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use FindOwnerTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveMemory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $MemoryResult = Memory::find($args['id']);

        if (!$MemoryResult) {
            throw new CustomValidationException("Memory-UPDATE-RECORD_NOT_FOUND", "رکورد برای به روز رسانی حافظه پیدا نشد", 404);

            //return Error::createLocatedError("Memory-UPDATE-RECORD_NOT_FOUND");
        }


        if ($MemoryResult->person_id !== $this->findOwner()->id) {
            // If person_id doesn't match, throw an exception
            throw new CustomValidationException("This is not your own memory.", "این خاطره خودتان نیست.", 403);
        }

        $args['editor_id'] = $this->userId;
        $MemoryResult_filled = $MemoryResult->fill($args);
        $MemoryResult->save();

        return $MemoryResult;
    }
}