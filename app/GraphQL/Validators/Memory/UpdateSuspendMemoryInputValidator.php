<?php

namespace App\GraphQL\Validators\Memory;

use Nuwave\Lighthouse\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\GraphQL\Enums\ConfirmMemoryStatus;
use App\Models\Memory;
use App\Traits\FindOwnerTrait;

use Exception;
use Log;

class UpdateSuspendMemoryInputValidator extends Validator
{
    protected $userId;

    public function rules(): array
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;

        // Log::info("UpdateSuspendMemoryInputValidator User ID: " . $this->userId);

        $confirmStatus = $this->arg('confirm_status');
       
        return [
            'confirm_status' => [
                'required',
                Rule::in([ConfirmMemoryStatus::Accept, ConfirmMemoryStatus::Reject, ConfirmMemoryStatus::Suspend]),
            ],
            'reject_cause' => [
                Rule::requiredIf(function () use ($confirmStatus) {
                    return $confirmStatus === ConfirmMemoryStatus::Reject->value;
                }),
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_status.required' => 'Confirm status is required.',
            'confirm_status.in' => 'Confirm status must be either "Accept" or "Reject".',
            'reject_cause.required' => 'Reject cause is required when confirm status is "reject".',
            'reject_cause.string' => 'Reject cause must be a string.',
        ];
    }
}
