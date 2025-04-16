<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;

use App\Exceptions\CustomValidationException;

use Exception;

final class CancelSenderRequest
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
    public function resolveCancelSenderRequest($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->user = $this->getUser();

        $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);


        $UserMergeRequestResult = UserMergeRequest::where($this->user->creator_id, $this->user->id)->first();

        if (!$UserMergeRequestResult) {
            throw new CustomValidationException("USERMERGEREQUEST-CANCEL-SENDER-RECORD_NOT_FOUND", "درخواست ادغام کاربر. لغو رکورد ارسال کننده. رکورد یافت نشد", 404);

            //return Error::createLocatedError("UserSendeRequest-UPDATE-RECORD_NOT_FOUND");
        }
        $UserMergeRequestResult->editor_id = $this->user->id;

        $UserMergeRequestResult_filled = $UserMergeRequestResult->fill($args);
        $UserMergeRequestResult->save();

        return $UserMergeRequestResult;

    }
}