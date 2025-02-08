<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\Traits\SmallClanTrait;
use App\Traits\HandlesPersonDeletion;
use Exception;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use Log;

final class DeletePerson
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    use HandlesPersonDeletion;

    protected $userId;
    protected $personId;

    /**
     * Delete a person
     *
     * @param mixed $rootValue
     * @param array $args
     * @param GraphQLContext|null $context
     * @param ResolveInfo $resolveInfo
     * @return Person|Error
     * @throws Exception
     */
    public function resolvePerson($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        try {
            $userId = auth()->id();
            $personId = $args['personId'] ?? null;

            $validationResult = $this->canDeletePerson($userId, $personId);
            if ($validationResult !== true) {
                return $validationResult; // Return error response
            }

            $person = Person::find($personId);
            $person->delete();

            Log::info("User {$userId} successfully deleted Person ID {$personId}");
            return $person;

        } catch (Exception $e) {
            Log::error("DeletePerson Mutation Error", ['error' => $e->getMessage(), 'personId' => $personId]);
            throw new Exception("Person-DELETE-ERROR_OCCURED");
        }
    }

   
}
