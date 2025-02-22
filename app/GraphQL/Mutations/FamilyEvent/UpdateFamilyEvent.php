<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;



final class UpdateFamilyEvent
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        try {

            $FamilyEventResult = $this->userAccessibility(FamilyEvent::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new FamilyEvent(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($FamilyEventResult, $args, $this->userId);

        // $this->userAccessibility(FamilyEvent::class, AuthAction::Delete, $args);

        // //args["user_id_creator"]=$user_id;
        // $FamilyEventResult = FamilyEvent::find($args['id']);

        // if (!$FamilyEventResult) {
        //     return Error::createLocatedError("FamilyEvent-UPDATE-RECORD_NOT_FOUND");
        // }

        // try {

        //     $FamilyEventResult = $this->userAccessibility(FamilyEvent::class, AuthAction::Update, $args);

        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }

        // $this->checkDuplicate(
        //     new FamilyEvent(),
        //     $args,
        //     ['id', 'editor_id', 'created_at', 'updated_at'],
        //     $args['id']
        // );

        // $args['editor_id'] = $this->userId;
        // $FamilyEventResult_filled = $FamilyEventResult->fill($args);
        // $FamilyEventResult->save();

        // return $FamilyEventResult;


    }
}