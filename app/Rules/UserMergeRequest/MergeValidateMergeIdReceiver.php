<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusSender;
use Log;

class MergeValidateMergeIdReceiver implements Rule
{
    protected $loggedInUserId;
    protected $allowedPersonIds = [];
    protected $invalidIds = [];
    protected $id;
    protected $user_receiver_id;

    public function __construct($loggedInUserId, $user_request_id)
    {
        $this->loggedInUserId = $loggedInUserId;
        $this->id = $user_request_id;
    }

    public function passes($attribute, $value)
    {
        $this->allowedPersonIds = $this->fetchAllowedPersonIds();

        $mergeIds = explode(',', $value);
        $this->invalidIds = array_diff($mergeIds, $this->allowedPersonIds);

        return empty($this->invalidIds);
    }

    public function message()
    {
        if (empty($this->allowedPersonIds)) {
            return 'No valid IDs are available for your complete or active relationships.';
        }

        return 'The input.merge_ids_receiver contains invalid IDs: '
            . implode(', ', $this->invalidIds)
            . '. Only the following IDs are allowed: '
            . implode(', ', $this->allowedPersonIds) . '.';
    }

    private function fetchAllowedPersonIds()
    {

        $this->user_receiver_id = UserMergeRequest::where('id', $this->id)->first()->user_receiver_id;

        $completeRelations = UserMergeRequest::where('status', MergeStatus::Complete)
            ->where('user_receiver_id', $this->user_receiver_id)
            ->orWhere('user_sender_id', $this->user_receiver_id)
            ->get();

        if ($completeRelations->isNotEmpty()) {
            return $this->getPersonIdsForCompleteRelations($completeRelations);
        }

        $activeRelations[] = $this->user_receiver_id;

        return $this->getPersonIdsForCreators($activeRelations);
    }

    private function getPersonIdsForCompleteRelations($relations)
    {
        $userIds = $relations->pluck('user_receiver_id')
            ->merge($relations->pluck('user_sender_id'))
            ->unique()
            ->toArray();

        return $this->getPersonIdsForCreators($userIds);
    }

    private function getPersonIdsForCreators($creatorIds)
    {
        if (empty($creatorIds)) {
            return [];
        }

        return Person::whereIn('creator_id', $creatorIds)
            ->pluck('id')
            ->toArray();
    }
}
