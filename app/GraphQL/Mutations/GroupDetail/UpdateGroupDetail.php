<?php

namespace App\GraphQL\Mutations\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;
use App\Exceptions\CustomValidationException;



final class UpdateGroupDetail
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
    public function resolveGroupDetail($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        // $this->userAccessibility(GroupDetail::class, AuthAction::Update, $args);


        // //args["user_id_creator"]=$user_id;
        // $GroupDetailResult=GroupDetail::find($args['id']);

        // if(!$GroupDetailResult)
        // {
        //     return Error::createLocatedError("GroupDetail-UPDATE-RECORD_NOT_FOUND");
        // }
        // try {

        //     $GroupDetailResult = $this->userAccessibility(GroupDetail::class, AuthAction::Delete, $args);

        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }


        // $this->checkDuplicate(
        //     new GroupDetail(),
        //     $args,
        //     ['id', 'editor_id', 'created_at', 'updated_at'],
        //     $args['id']
        // );
        // $args['editor_id'] = $this->userId;
        // $GroupDetailResult_filled = $GroupDetailResult->fill($args);
        // $GroupDetailResult->save();

        // return $GroupDetailResult;
        try {

            $GroupDetailResult = $this->userAccessibility(GroupDetail::class, AuthAction::Update, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new GroupDetail(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($GroupDetailResult, $args, $this->userId);

    }
}