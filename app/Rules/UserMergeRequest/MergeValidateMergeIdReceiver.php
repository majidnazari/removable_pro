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

    public function __construct($loggedInUserId)
    {
        $this->loggedInUserId = $loggedInUserId;
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
        // Fetch Complete Relationships
        $completeRelations = UserMergeRequest::where('status', MergeStatus::Complete)
            //->where('user_receiver_id', $this->loggedInUserId)
            ->where('user_sender_id', $this->loggedInUserId)
            ->get();

            Log::info("the complete are:". json_encode($completeRelations));

        if ($completeRelations->isNotEmpty()) {
            return $this->getPersonIdsForCompleteRelations($completeRelations);
        }

        // Fetch Active Relationships if no Complete Relations found
        $activeRelations = UserMergeRequest::where('request_status_sender', RequestStatusSender::Active)
            ->where('request_status_receiver', RequestStatusSender::Active)
            ->where('user_sender_id', $this->loggedInUserId)
            ->pluck('user_sender_id')
            //->pluck('user_receiver_id')
            ->toArray();

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
