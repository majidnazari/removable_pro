<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\User;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\MarriageStatus;
use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\SmallClanTrait;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\AuthorizesMutation;
use App\Traits\GetAllBloodUsersRelationInClanFromHeads;
use App\Traits\FindOwnerTrait;
use App\GraphQL\Enums\AuthAction;
use App\Events\DeleteUserFromAllRelations;

use App\Exceptions\CustomValidationException;

use GraphQL\Error\Error;
use Exception;
use App\Events\PersonDeletedEvent;
use Log;

final class DeleteJustPersonWithFamilyTreeRelation
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;
    use PersonAncestryWithCompleteMerge;
    use AuthorizesMutation;
    use GetAllBloodUsersRelationInClanFromHeads;
    use FindOwnerTrait;


    public function resolveDeletePersonWithFamilyTreeRelation($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $personId = $args['person_id'];

        $owner = $this->findOwner();

        if ($owner->id != $personId) {
            throw new CustomValidationException("You are not allowed to use this method for others!", "شما مجاز به استفاده از این روش برای دیگران نیستید!", 403);

        }

        try {

            $allBloodUserIds = $this->getAllBloodUsersInClanFromHeads($this->userId);

            // Remove the user themselves from the result array
            $allBloodUserIdsWithoutItself = array_filter($allBloodUserIds, fn($id) => $id != $this->userId);


            if (empty($allBloodUserIdsWithoutItself) || !is_array($allBloodUserIdsWithoutItself) || count($allBloodUserIdsWithoutItself) === 0) {
                throw new CustomValidationException("You must use the delete relation person method instead.", "شما باید از روش حذف شخص رابطه استفاده کنید.", 400);

            }

            DB::transaction(function () use ($personId) {
                // Soft delete all related records
                event(new DeleteUserFromAllRelations($personId));

                // Update person->isowner = 0 after deletion
                $person = Person::find($personId);

                if (!$person) {
                    throw new CustomValidationException("Person not found", "شخص پیدا نشد", 400);

                }

                $person->update(['is_owner' => 0]);

                $user = User::where('id', $this->userId)->first();
                $user->delete();


            });


        } catch (CustomValidationException $e) {

            Log::error("Failed  resolveDeletePersonWithFamilyTreeRelation: " . $e->getMessage());

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

    }


}
