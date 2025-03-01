<?php

namespace App\GraphQL\Mutations\UserDetail;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;

use Exception;

final class UpdateUserDetail
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
    public function resolveUserDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();
        // $this->userAccessibility(UserDetail::class, AuthAction::Update, $args);

        // $UserDetailResult=UserDetail::find($args['id']);

        // if(!$UserDetailResult)
        // {
        //     return Error::createLocatedError("UserDetail-UPDATE-RECORD_NOT_FOUND");
        // }

        // if (isset($args['mobile']) && ( $this->user->mobile !== $args['mobile']) ) {
        //     return Error::createLocatedError("The provided mobile does not belong to the logged-in user.");
        // }
        // $this->checkDuplicate(
        //     new UserDetail(),
        //     $args,
        //     ['id','editor_id','created_at','mobile', 'updated_at','status'],
        //     $args['id']
        // );
        // $args['editor_id']=$this->user->id;

        // $UserDetailResult_filled= $UserDetailResult->fill($args);
        // $UserDetailResult->save();       

        // return $UserDetailResult;

        try {

            $UserDetailResult = $this->userAccessibility(UserDetail::class, AuthAction::Update, $args);
            if (isset($args['mobile']) && ($this->user->mobile !== $args['mobile'])) {
                return Error::createLocatedError("The provided mobile does not belong to the logged-in user.");
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new UserDetail(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($UserDetailResult, $args, $this->userId);



    }
}