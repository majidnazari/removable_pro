<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteFamilyEvent
{
    use AuthUserTrait;
    use AuthorizesMutation;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        // $this->userAccessibility(FamilyEvent::class, AuthAction::Delete, $args);


        // $FamilyEventResult = FamilyEvent::find($args['id']);

        // if (!$FamilyEventResult) {
        //     return Error::createLocatedError("FamilyEvent-DELETE-RECORD_NOT_FOUND");
        // }
        try {

            $FamilyEventResult = $this->userAccessibility(FamilyEvent::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $FamilyEventResult->editor_id = $this->userId;
        $FamilyEventResult->save();

        $FamilyEventResult_filled = $FamilyEventResult->delete();
        return $FamilyEventResult;


    }
}