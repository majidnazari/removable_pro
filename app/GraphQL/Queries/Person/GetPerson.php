<?php

namespace App\GraphQL\Queries\Person;

use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusReceiver;
use App\GraphQL\Enums\RequestStatusSender;
use App\Models\Person;
use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\FindOwnerTrait;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\PersonAncestryWithActiveMerge;
use App\Traits\PersonDescendantsWithCompleteMerge;
use App\Traits\GetAllBloodPersonsInClanFromHeads;
use App\Traits\GetAllBloodPersonsWithSpousesInClanFromHeads;
use App\Traits\GetAllUsersRelationInClanFromHeads;
use App\Traits\BloodyPersonAncestry;
use App\Exceptions\CustomValidationException;

use Log;

final class GetPerson
{
    use AuthUserTrait;
    use AuthorizesUser;
    use FindOwnerTrait;
    use FindOwnerTrait;
    use PersonAncestryWithCompleteMerge;
    use PersonAncestryWithActiveMerge;
    use GetAllBloodPersonsInClanFromHeads;
    use GetAllBloodPersonsWithSpousesInClanFromHeads;
    use GetAllUsersRelationInClanFromHeads;
    use BloodyPersonAncestry;


    private $rootAncestors = [];

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolvePerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $this->getUser();
        $Person = $this->findUser($args['id']);//Person::where('id', $args['id']);
        $getAllUsersInClanFromHeads = $this->getAllUsersInClanFromHeads($user->id);

        return $Person;
    }

    public function findUser($id)
    {
        $person = Person::find($id);
        if ($person) {
            return $person;
        } else {
            throw new CustomValidationException("The person not found!", "فرد پیدا نشد!", 404);
        }
    }

    public function resolvePersonAncestryWithCompleteMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        return $this->getPersonAncestryWithCompleteMerge($user_id, $depth);
    }

    public function resolvePersonAncestryWithActiveMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        return $this->getPersonAncestryWithActiveMerge($user_id, $depth);
    }

    public function resolveBloodyPersonAncestry($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        return $this->getBloodyPersonAncestry($user_id, $depth);
    }


}