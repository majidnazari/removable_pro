<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\RequestStatusSender;
use App\GraphQL\Enums\MergeStatus;
use App\models\User;
use App\models\Person;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;

final class UpdateRequestSender
{
    use AuthUserTrait;
    use AuthorizesMutation;

    protected $user_sender_id;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUpdateRequestSender($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user_sender_id = $this->getUserId();
        $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);

        $data = [
            "editor_id" => $this->user_sender_id,
            "request_status_sender" => $args['request_status_sender'] ?? RequestStatusSender::Suspend
        ];

        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
        //->where('request_status_sender',  RequestStatusSender::Active->value)

        if (!$UserMergeRequest) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-SENDER-RECORD_NOT_FOUND", "درخواست ادغام کاربر. به روز رسانی ارسال کننده. رکورد یافت نشد", 404);
        }

        $is_exist = UserMergeRequest::where('user_sender_id', $this->user_sender_id)
            ->where('id', '!=', $args['id'])
            // ->where('user_receiver_id', $user_receiver->id)
            ->where('request_status_sender', RequestStatusSender::Active->value)
            ->where('status', '!=', MergeStatus::Complete->value)
            ->first();

        if ($is_exist) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-SENDER-YOU_HAVE_ONE_ACTIVE_REQUEST", "درخواست ادغام کاربر. به روز رسانی ارسال کننده. در حال حاضر رکورد فعال وجود دارد.", 409);

        }

        if ($UserMergeRequest->creator_id != $this->user_sender_id) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-SENDER-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS", "درخواست ادغام کاربر. به روز رسانی ارسال کننده. شما مجاز به تغییر درخواست خود هستید  ", 403);
        }
        $UserMergeRequestResult = $UserMergeRequest->fill($data);
        $UserMergeRequestResult->save();

        return $UserMergeRequestResult;
    }
}