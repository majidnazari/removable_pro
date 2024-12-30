<?php

namespace App\GraphQL\Mutations\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use Exception;


final class DeleteGroupDetail
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
    public function resolveGroupDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        // $this->userAccessibility(GroupDetail::class, AuthAction::Delete, $args);


        // $GroupDetailResult = GroupDetail::find($args['id']);

        // if (!$GroupDetailResult) {
        //     return Error::createLocatedError("GroupDetail-DELETE-RECORD_NOT_FOUND");
        // }

        try {

            $GroupDetailResult = $this->userAccessibility(GroupDetail::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }


        $GroupDetailResult->editor_id = $this->userId;
        $GroupDetailResult->save();

        $GroupDetailResult_filled = $GroupDetailResult->delete();
        return $GroupDetailResult;


    }
}