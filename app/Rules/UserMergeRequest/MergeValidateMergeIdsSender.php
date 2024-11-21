<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use App\Models\Person;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use Illuminate\Support\Facades\Log;

class MergeValidateMergeIdsSender implements Rule
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
        Log::info("MergeValidateMergeIdsSender: Checking {$attribute} with value: {$value}");

        // Step 1: Fetch Complete Relationships
        $completeRelations = UserMergeRequest::where('status', MergeStatus::Complete)
            ->where('user_sender_id', $this->loggedInUserId)
            ->get();

        Log::info("Complete Relationships for user {$this->loggedInUserId}: " . json_encode($completeRelations));

        foreach ($completeRelations as $relation) {
            // Add sender's people
            $senderPersons = Person::where('creator_id', $relation->user_sender_id)
                ->pluck('id')
                ->toArray();

            // Add receiver's people
            $receiverPersons = Person::where('creator_id', $relation->user_reciver_id)
                ->pluck('id')
                ->toArray();

            // Merge IDs
            $this->allowedPersonIds = array_merge($this->allowedPersonIds, $senderPersons, $receiverPersons);
        }

        if($completeRelations->isNotEmpty())
        {
            // Step 2: Fetch Active Relationships
            $activeRelations = UserMergeRequest::where('status', MergeStatus::Active)
            ->where('user_sender_id', $this->loggedInUserId)
            ->get();

            Log::info("Active Relationships for user {$this->loggedInUserId}: " . json_encode($activeRelations));

            foreach ($activeRelations as $relation) {
            // Add only sender's own people
            $senderPersons = Person::where('creator_id', $relation->user_sender_id)
                ->pluck('id')
                ->toArray();

            $this->allowedPersonIds = array_merge($this->allowedPersonIds, $senderPersons);
            }

            // Remove duplicate IDs
            $this->allowedPersonIds = array_unique($this->allowedPersonIds);

        }
       
        Log::info("Final Allowed Person IDs for input.merge_ids_sender: " . json_encode($this->allowedPersonIds));

        // Step 3: Validate Input Merge IDs
        $mergeIds = explode(',', $value);
        $this->invalidIds = array_diff($mergeIds, $this->allowedPersonIds);

        Log::info("Invalid IDs for input.merge_ids_sender: " . json_encode($this->invalidIds));

        // Validation passes if there are no invalid IDs
        return empty($this->invalidIds);
    }

    public function message()
    {
        if (empty($this->allowedPersonIds)) {
            return 'No valid IDs are available for your complete or active relationships.';
        }

        return 'The input.merge_ids_sender contains invalid IDs: ' 
            . implode(', ', $this->invalidIds) 
            . '. Only the following IDs are allowed: ' 
            . implode(', ', $this->allowedPersonIds) . '.';
    }
}
