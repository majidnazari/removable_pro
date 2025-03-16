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


use GraphQL\Error\Error;
use Exception;
use App\Events\PersonDeletedEvent;
use Log;

final class DeleteJustOwnPerson
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;
    use PersonAncestryWithCompleteMerge;
    use AuthorizesMutation;
    use GetAllBloodUsersRelationInClanFromHeads;
    use FindOwnerTrait;


    public function resolveDeleteJustOwnPerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $personId = $args['id'];

        $owner=$this->findOwner();

        if ($owner->id != $personId) {
            throw new Exception("You are not allowed to use this method for others!");
        }

        try {

            $result = $this->getAllBloodUsersInClanFromHeads($this->userId);
            Log::info("The result is: " . json_encode($result));

            if (empty($result) || !is_array($result) || count($result) === 0) {
                Log::info("The result is empty or null.");
                throw new Exception("You must use the delete relation person method instead.");
            }

            DB::transaction(function () use ($personId) {
                // Soft delete all related records
                event(new DeleteUserFromAllRelations($personId));

                // Update person->isowner = 0 after deletion
                $person = Person::find($personId);

                if (!$person) {
                    throw new Exception("Person not found");
                }

                $person->update(['is_owner' => 0]);

                $user=User::where('id',$this->userId)->first();
                $user->delete();
               

                Log::info("Updated person {$personId} -> isowner = 0");
            });


            // DB::transaction(function () use ($PersonResult) {
            //     $person = Person::find( $personId);

            //     if (!$person) {
            //         throw new Exception("Person not found");
            //     }

            //     $person->delete();

            //     // Fire the event to handle other related deletions
            //     event(new PersonDeletedEvent($PersonResult->id));
            // });

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

    }


}
