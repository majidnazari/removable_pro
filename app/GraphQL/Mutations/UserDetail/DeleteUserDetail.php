<?php

namespace App\GraphQL\Mutations\UserDetail;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;

use Exception;
final class DeleteUserDetail
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
    public function resolveUserDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        // $this->userAccessibility(UserDetail::class, AuthAction::Delete, $args);

        // $UserDetailResult = UserDetail::find($args['id']);

        // if (!$UserDetailResult) {
        //     return Error::createLocatedError("UserDetail-DELETE-RECORD_NOT_FOUND");
        // }
        // $UserDetailResult->editor_id = $this->userId;
        // $UserDetailResult->save();


        // $UserDetailResult_filled = $UserDetailResult->delete();
        // return $UserDetailResult;

        try {

            $UserDetailResult = $this->userAccessibility(UserDetail::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($UserDetailResult, $args, $this->userId);




    }
}