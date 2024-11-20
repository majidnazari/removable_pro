<?php

namespace App\Rules\UserMergeRequest;

use App\Models\UserMergeRequest;
use Illuminate\Contracts\Validation\Rule;
use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\Status;

use App\Models\Person;



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
        $this->attributeName = $attribute; // Capture the attribute name

        // Fetch all active relationships where the logged-in user is a sender or receiver
        $activeRelations = UserMergeRequest::where('status', Mergestatus::Active)
            ->where(function ($query) {
                $query->where('user_sender_id', $this->loggedInUserId)
                    ->orWhere('user_reciver_id', $this->loggedInUserId);
            })
            ->get();
        // Extract user IDs of sender and receiver from active relationships
        $relatedUserIds = $activeRelations->flatMap(function ($relation) {
            return [$relation->user_sender_id, $relation->user_reciver_id];
        })->unique()->toArray();

        // Step 2: Fetch all person IDs created by the related users with status 'Active'
        $this->allowedPersonIds = Person::whereIn('creator_id', $relatedUserIds)
            ->where('status', status::Active)
            ->pluck('id')
            ->toArray();

            // Convert the input merge IDs to an array
            $mergeIds = explode(',', $value);

            // Check for any IDs not in the allowed range
            $this->invalidIds = array_diff($mergeIds, $this->allowedPersonIds);

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
