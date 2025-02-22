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
    protected $deletePersonIds = [];

    /**
     * Delete a person
     *
     * @param mixed $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Person|Error
     * @throws Exception
     */
    public function resolvePerson($rootValue, array $args, GraphQLContext $context ,ResolveInfo $resolveInfo)
    {
        try {
            $this->userId = auth()->id();
            $personId = $args['personId'] ?? null;

            if (!$personId) {
                throw new Exception("Person ID is required.");
                
            }

            // Validate deletion permission
            $validationResult = $this->canDeletePerson($this->userId, $personId);
            if ($validationResult !== true) {
                return $validationResult;
            }

             Log::info("candelete triat  result is :{$validationResult}" );


            // // Start transaction
            // DB::beginTransaction();

            // $this->deletePersonIds[] = $personId;

            // // Get all active spouses of the person
            // $spouseIds = PersonMarriage::where($this->getSpouseColumn($personId), $personId)
            //     ->where('status', Status::Active)
            //     ->pluck($this->getSpouseOppositeColumn($personId))
            //     ->toArray();

            // // Validate all spouses before deletion
            // foreach ($spouseIds as $spouseId) {
            //     $spouseValidation = $this->canDeletePerson($this->userId, $spouseId);
            //     if ($spouseValidation !== true) {
            //         DB::rollBack(); // Rollback if any spouse cannot be deleted
            //         return $spouseValidation;
            //     }
            //     $this->deletePersonIds[] = $spouseId;
            // }

            // // Delete all collected person IDs
            // Person::whereIn('id', $this->deletePersonIds)->delete();

            // DB::commit(); // Commit transaction

            // Log::info("User {$this->userId} successfully deleted Persons: " . implode(',', $this->deletePersonIds));

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("DeletePerson Mutation Error", [
                'error' => $e->getMessage(),
                'personId' => $args['personId'] ?? 'N/A',
                'userId' => $this->userId
            ]);
            throw new Exception("Person-DELETE-ERROR_OCCURRED");
        }
    }

    /**
     * Get the column name for finding a person's spouse in PersonMarriage.
     */
    private function getSpouseColumn($personId)
    {
        $person = Person::find($personId);
        return $person->gender ? 'man_id' : 'woman_id';
    }

    /**
     * Get the opposite spouse column name in PersonMarriage.
     */
    private function getSpouseOppositeColumn($personId)
    {
        $person = Person::find($personId);
        return $person->gender ? 'woman_id' : 'man_id';
    }
}

