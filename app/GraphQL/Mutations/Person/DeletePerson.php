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
use Exception;
use Log;

final class DeletePerson
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    use SmallClanTrait;

    protected  $userId;
    protected  $personId;

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
            $this->userId = $this->getUserId();
            $this->personId = $args['personId'] ?? null;

            if (!$this->personId) {
                return Error::createLocatedError("Person-DELETE-MISSING_PERSON_ID");
            }

            Log::info("User {$this->userId} attempting to delete Person ID {$this->personId}");

            $person = Person::find($this->personId);
            if (!$person) {
                return Error::createLocatedError("Person-DELETE-PERSON_NOT_FOUND");
            }

            // Prevent deletion if the person is an owner
            if ( $person->is_owner) {
                return Error::createLocatedError("Person-DELETE-THIS_IS_OWNER");
            }

            // Retrieve allowed user IDs
            $allowedUserIds = collect($this->getAllUserIdsSmallClan($this->personId));

            if (!$allowedUserIds->contains($this->userId)) {
                return Error::createLocatedError("Person-DELETE-YOU_DONT_HAVE_PERMISSION");
            }

            // Prevent deletion if the person has children
            if (!empty($this->getAllChildren($this->personId))) {
                return Error::createLocatedError("Person-DELETE-HAS_CHILDREN");
            }

            // Delete the person
            $person->delete();

            Log::info("User {$this->userId} successfully deleted Person ID {$this->personId}");
            return $person;

        } catch (Exception $e) {
            Log::error("DeletePerson Mutation Error", ['error' => $e->getMessage(), 'personId' => $this->personId]);
            throw new Exception("Person-DELETE-ERROR_OCCURED");
        }
    }
}
