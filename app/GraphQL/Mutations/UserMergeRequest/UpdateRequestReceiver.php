<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusReceiver;
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

final class UpdateRequestReceiver
{
    use AuthUserTrait;
    use AuthorizesMutation;
    protected $user_receiver_id;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUpdateRequestReceiver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user_receiver_id = $this->getUserId();
        //$this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args);

        // Specify columns to check (optional)
        $columnsToCheck = ['user_receiver_id']; // You can remove or pass null to check just 'creator_id'

        // Check if the user matches any of the columns in the array
        $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args, $columnsToCheck);


        $data = [
            // "editor_id" => $user_sender_id,
            "request_status_receiver" => $args['request_status_receiver'] ?? RequestStatusReceiver::Suspend->value
        ];

//       Log::info("the args are:" . json_encode($UserMergeRequestResult));
        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
        if (!$UserMergeRequest) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-RECORD_NOT_FOUND", "درخواست ادغام کاربر. به روز رسانی دریافت کننده. رکورد یافت نشد", 404);

            //return Error::createLocatedError("UserSendRequest-NOT_FOUND");
        }

        $is_exist = UserMergeRequest::where('user_sender_id', $this->user_receiver_id)
            ->where('id', '!=', $args['id'])
            // ->where('user_receiver_id', $user_receiver->id)
            ->where('request_status_sender', RequestStatusSender::Active->value)
            ->where('status', '!=', MergeStatus::Complete->value)

            ->first();

        if ($is_exist) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-YOU_HAVE_ONE_ACTIVE_REQUEST", "درخواست ادغام کاربر. به روز رسانی دریافت کننده. در حال حاضر رکورد فعال وجود دارد.", 409);

            //return Error::createLocatedError("UserSendRequest-YOU_HAVE_ONE_ACTIVE_REQUEST");
        }

        if ($UserMergeRequest->user_receiver_id != $this->user_receiver_id) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS", "درخواست ادغام کاربر. به روز رسانی دریافت کننده. شما مجاز به تغییر درخواست خود هستید  ", 403);

            //return Error::createLocatedError("UserSendRequest-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS");

        }

//       Log::info("the active sttaus us:".RequestStatusSender::Active->value);
        if ($UserMergeRequest->request_status_sender != RequestStatusSender::Active->value) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-FIRST_SENDER_MUST_MAKE_REQUEST_ACTIVE", "درخواست ادغام کاربر. به روز رسانی دریافت کننده. باید ابتدا ارسال کننده تایید کند ", 403);

            //return Error::createLocatedError("UserSendRequest-FIRST_SENDER_MUST_MAKE_REQUEST_ACTIVE");

        }
        $UserMergeRequestResult = $UserMergeRequest->fill($data);
        $UserMergeRequestResult->save();

        return $UserMergeRequestResult;
    }
}