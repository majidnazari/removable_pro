<?php

namespace App\Rules\UserMergeRequest;

use App\GraphQL\Enums\RequestStatusSender;
use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;

use App\Models\Person;
use Log;



class MergeValidateMergeIdsWithinActiveRelations implements Rule
{
    protected $loggedInUserId;
    protected $allowedPersonIds = [];
    protected $invalidIds = [];
    protected $attributeName; // Store the attribute name

    public function __construct($loggedInUserId)
    {
        $this->loggedInUserId = $loggedInUserId;
    }

    public function passes($attribute, $value)
    {
//       Log::info("MergeValidateMergeIdsWithinActiveRelations: Checking {$attribute} with value: {$value}");
        $this->attributeName = $attribute; // Capture the attribute name

        $this->allowedPersonIds = []; // Initialize allowed person IDs

        // Handle Active Relationships
        $activeRelation = UserMergeRequest::where('status', RequestStatusSender::Active->value) // Active status
            ->where(function ($query) {
                $query->where('user_sender_id', $this->loggedInUserId)
                    ->orWhere('user_receiver_id', $this->loggedInUserId);
            })
            ->first();

        if ($activeRelation) {
            if ($this->attributeName === 'input.merge_ids_sender') {
                // Fetch persons created by user_sender_id
                $this->allowedPersonIds = array_merge(
                    $this->allowedPersonIds,
                    Person::where('creator_id', $activeRelation->user_sender_id)
                        ->pluck('id')
                        ->toArray()
                );
//              Log::info("Active Relationship - merge_ids_sender allowedPersonIds: " . json_encode($this->allowedPersonIds));
            } elseif ($this->attributeName === 'input.merge_ids_receiver') {
                // Fetch persons created by user_receiver_id
                $this->allowedPersonIds = array_merge(
                    $this->allowedPersonIds,
                    Person::where('creator_id', $activeRelation->user_receiver_id)
                        ->pluck('id')
                        ->toArray()
                );
//               Log::info("Active Relationship - merge_ids_receiver allowedPersonIds: " . json_encode($this->allowedPersonIds));
            }
        } 
        //else {
//           Log::info("No active relationship found for user {$this->loggedInUserId}");
        //}

        // Handle Complete Relationships
        $completeRelations = UserMergeRequest::where('status', Mergestatus::Complete) // Complete status
            ->where(function ($query) {
                $query->where('user_sender_id', $this->loggedInUserId)
                    ->orWhere('user_receiver_id', $this->loggedInUserId);
            })
            ->get();

        if ($completeRelations->isNotEmpty()) {
            foreach ($completeRelations as $relation) {
                // Fetch all persons created by both sender and receiver
                $this->allowedPersonIds = array_merge(
                    $this->allowedPersonIds,
                    Person::where('creator_id', $relation->user_sender_id)
                        ->pluck('id')
                        ->toArray(),
                    Person::where('creator_id', $relation->user_receiver_id)
                        ->pluck('id')
                        ->toArray()
                );
            }

//           Log::info("Complete Relationships - merged allowedPersonIds: " . json_encode($this->allowedPersonIds));
        }

        // Remove duplicate IDs
        $this->allowedPersonIds = array_unique($this->allowedPersonIds);
//      Log::info("Final Allowed Person IDs for {$this->attributeName}: " . json_encode($this->allowedPersonIds));

        // Validate input merge IDs
        $mergeIds = explode(',', $value);
        $this->invalidIds = array_diff($mergeIds, $this->allowedPersonIds);
//       Log::info("Invalid IDs for {$this->attributeName}: " . json_encode($this->invalidIds));

        // Validation passes if there are no invalid IDs
        return empty($this->invalidIds);
    }


    public function message()
    {
        if (empty($this->allowedPersonIds)) {
            return 'The ' . str_replace('_', ' ', $this->attributeName) . ' contains invalid IDs. No valid IDs are available for your active relationships.';
        }

        return 'The ' . str_replace('_', ' ', $this->attributeName) . ' contains invalid IDs: ' 
            . implode(', ', $this->invalidIds) 
            . '. Only the following IDs are allowed: ' 
            . implode(', ', $this->allowedPersonIds) . '.';
    }
}
