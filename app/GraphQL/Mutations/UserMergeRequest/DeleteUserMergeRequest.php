<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;


use Exception;

final class DeleteUserMergeRequest
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
    public function resolveUserMergeRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();

        //    $this->userAccessibility(UserMergeRequest::class, AuthAction::Delete, $args);

        //     $UserMergeRequestResult=UserMergeRequest::find($args['id']);

        //     if(!$UserMergeRequestResult)
        //     {
        //         return Error::createLocatedError("UserMergeRequest-DELETE-RECORD_NOT_FOUND");
        //     }

        //     $UserMergeRequestResult->editor_id= $this->user->id;
        //     $UserMergeRequestResult->save(); 

        //     $UserMergeRequestResult_filled= $UserMergeRequestResult->delete();  
        //     return $UserMergeRequestResult;
        try {

            $UserMergeRequestResult = $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
     
        return $this->updateModel($UserMergeRequestResult, $args, $this->userId);

    }
}