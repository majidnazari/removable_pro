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
use App\Exceptions\CustomValidationException;

use GraphQL\Error\Error;
use Exception;
use App\Events\PersonDeletedEvent;
use Log;

final class DeletePersonWithAllTraces
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;
    use PersonAncestryWithCompleteMerge;
    use AuthorizesMutation;

    public function resolveDeletePersonWithAllTraces($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $personId = $args['person_id'];


        try {

            $PersonResult = $this->userAccessibility(Person::class, AuthAction::Delete, $args);

            DB::transaction(function () use ($PersonResult) {
                
                // Fire the event to handle other related deletions
                event(new PersonDeletedEvent($PersonResult->id));
            });

        } catch (CustomValidationException $e) {

           // Log::error("Failed  resolveDeletePersonWithAllTraces: " . $e->getMessage());

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

    }


}
