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
use App\Traits\DeletFamilyTreeRelationWithPersonTrait;
use Exception;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use Log;
use App\Exceptions\CustomValidationException;


final class DeleteFamilyTreeRelationWithPerson
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    use DeletFamilyTreeRelationWithPersonTrait;

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
    public function resolveFamilyTreeRelationWithPerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool|Error
    {
        try {
            $this->userId = auth()->id();
            Log::info("User ID logged in: {$this->userId}");

            $personId = $args['personId'] ?? null;
            if (!$personId) {
                throw new CustomValidationException("Person ID is required.", "شناسه شخصی الزامی است.", 400);

                //throw new Exception("Person ID is required.");
            }

            Log::info("Attempting to delete Person ID: {$personId}");

            // Validate deletion permission
            $validationResult = $this->deleteFamilyTreeRelationWithPerson($personId);

            if ($validationResult !== true) {
                Log::warning("Person ID {$personId} deletion failed: {$validationResult}");
                throw new CustomValidationException($validationResult, $validationResult, 400);

                //throw new Error($validationResult); // Properly throw the GraphQL error
            }

            Log::info("Person ID {$personId} successfully deleted.");
            return true;
        } catch (CustomValidationException $e) {

            Log::error("Failed to create spouses: " . $e->getMessage());

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
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


