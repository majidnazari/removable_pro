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
            Log::info("User ID logged in: {$this->userId}");
    
            $personId = $args['personId'] ?? null;
            if (!$personId) {
                throw new Exception("Person ID is required.");
            }
    
            Log::info("Attempting to delete Person ID: {$personId}");
    
            // Validate deletion permission
            $validationResult = $this->deletePerson($personId);
    
            if ($validationResult !== true) {
                Log::warning("Person ID {$personId} deletion failed: {$validationResult}");
                throw new Error($validationResult); // Properly throw the GraphQL error
            }
    
            Log::info("Person ID {$personId} successfully deleted.");
            return true;
        } catch (Exception $e) {
            Log::error("DeletePerson Mutation Error", [
                'error' => $e->getMessage(),
                'personId' => $args['personId'] ?? 'N/A',
                'userId' => $this->userId
            ]);
            throw new Error($e->getMessage()); // Ensure error is thrown, not returned
        }
    }

}

