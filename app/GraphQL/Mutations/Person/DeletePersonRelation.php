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

final class DeletePersonRelation
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
    public function resolvePersonRelation($rootValue, array $args, GraphQLContext $context ,ResolveInfo $resolveInfo): bool|Error   
    {
        try {
            $this->userId = auth()->id();
           // Log::info("the user id loggedi n is :" . $this->userId);
            $personId = $args['personId'] ?? null;

            if (!$personId) {
                throw new Exception("Person ID is required.");
                
            }

            //Log::info("candelete triat  result is :{$validationResult}" );

            // Validate deletion permission
            $validationResult = $this->DeletePersonRelation($this->userId, $personId);
            if ($validationResult !== true) {
                return $validationResult;
            }

             Log::info("candelete triat  result is :{$validationResult}" );


            return true;
        } catch (Exception $e) {
           // DB::rollBack();
            Log::error("DeletePerson Mutation Error", [
                'error' => $e->getMessage(),
                'personId' => $args['personId'] ?? 'N/A',
                'userId' => $this->userId
            ]);
            throw new Exception("Person-DELETE-ERROR_OCCURRED");
        }
    }

}

