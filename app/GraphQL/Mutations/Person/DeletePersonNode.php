<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
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
use App\GraphQL\Enums\AuthAction;

use GraphQL\Error\Error;
use Exception;
use App\Events\PersonDeletedEvent;
use Log;

final class DeletePersonNode
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;
    use PersonAncestryWithCompleteMerge;
    use AuthorizesMutation;



    public function resolveDeletePersonNode($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $personId = $args['id'];


        try {

            $PersonResult = $this->userAccessibility(Person::class, AuthAction::Delete, $args);

            DB::transaction(function () use ($PersonResult) {
                // $person = Person::find($personId);

                // if (!$person) {
                //     throw new Exception("Person not found");
                // }

                // $person->delete();

                // Fire the event to handle other related deletions
                event(new PersonDeletedEvent($PersonResult->id));
            });

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

    }


}
