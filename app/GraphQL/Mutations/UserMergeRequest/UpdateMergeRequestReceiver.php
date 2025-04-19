<?php

namespace App\GraphQL\Mutations\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusReceiver;
use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusSender;
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

final class UpdateMergeRequestReceiver
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
    public function resolveUpdateMergeRequestreceiver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user_receiver_id = $this->getUserId();

        $columnsToCheck = ['user_receiver_id']; // You can remove or pass null to check just 'creator_id'

        // Check if the user matches any of the columns in the array
        $this->userAccessibility(UserMergeRequest::class, AuthAction::Update, $args, $columnsToCheck);

        $data = [
            "merge_status_receiver" => $args['merge_status_receiver'] ?? RequestStatusReceiver::Suspend->value
        ];

        $UserMergeRequest = UserMergeRequest::where('id', $args['id'])->first();
        if (!$UserMergeRequest) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-RECORD_NOT_FOUND", "درخواست ادغام کاربر. به روزرسانی گیرنده. رکورد یافت نشد", 404);
        }



        $is_exist = UserMergeRequest::where('user_receiver_id', $this->user_receiver_id)
            ->where('id', '!=', $args['id'])
            // ->where('user_receiver_id', $user_receiver->id)
            ->where('request_status_sender', RequestStatusSender::Active->value)
            ->where('request_status_receiver', RequestStatusReceiver::Active->value)
            ->where('merge_status_sender', RequestStatusSender::Active->value)
            ->where('status', '!=', MergeStatus::Complete->value)
            ->first();

        if ($is_exist) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-YOU_HAVE_ONE_ACTIVE_REQUEST", "درخواست ادغام کاربر. به روزرسانی گیرنده .درخواست فعال وجود دارد", 409);

        }

        if ($UserMergeRequest->user_receiver_id != $this->user_receiver_id) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-YOU_CAN_JUST_CHANGE_YOUR_OWN_REQUESTS", "درخواست ادغام کاربر. به روزرسانی گیرنده .شما فقط می توانید درخواست های خود را تغییر دهید", 403);

        }


        //       Log::info("the active sttaus us:".RequestStatusSender::Active->value);
        if ($UserMergeRequest->request_status_sender != RequestStatusSender::Active->value) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-FIRST_SENDER_MUST_MAKE_REQUEST_ACTIVE1", "درخواست ادغام کاربر. به روزرسانی گیرنده .ارسال کننده باید تایید", 403);

        }

        if ($UserMergeRequest->merge_status_sender != RequestStatusSender::Active->value && ($args['merge_status_receiver'] === RequestStatusReceiver::Active->value)) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-FIRST_SENDER_MUST_MAKE_REQUEST_ACTIVE2", "درخواست ادغام کاربر. به روزرسانی گیرنده .ارسال کننده باید تایید", 403);

        }
        if (
            ($args['merge_status_receiver'] == RequestStatusSender::Active->value) &&
            ($UserMergeRequest->request_status_sender != RequestStatusSender::Active->value ||
                $UserMergeRequest->request_status_receiver != RequestStatusSender::Active->value ||
                $UserMergeRequest->merge_status_sender != RequestStatusSender::Active->value)
        ) {
            throw new CustomValidationException("USERMERGEREQUEST-UPDATE-RECEIVER-ALL_STATUS_MUST_BE_ACTIVE", "درخواست ادغام کاربر. به روزرسانی گیرنده . تمام وضعیت ها باید فعال باشند", 403);
        }

        $UserMergeRequestResult = $UserMergeRequest->fill($data);
        $UserMergeRequestResult->save();

        return $UserMergeRequestResult;
    }
}