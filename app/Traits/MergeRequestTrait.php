<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\AuthUserTrait;
use Log;

trait MergeRequestTrait
{
    use AuthUserTrait;
    /**
     * Get all related user IDs for the logged-in user.
     *
     * @return array
     */
    public function getRelatedUserIds()
    {

        // Get the logged-in user ID
        $user = $this->getUser(); // Assuming you're using Laravel's built-in auth system

        // Fetch records where the logged-in user is either the sender or receiver and the request status is 'Complete' (status 4)
        $relatedUserIds = UserMergeRequest::where('status', MergeStatus::Complete->value)
            ->where(function ($query) use ($user) {
                // Either the user is the sender or the receiver
                $query->where('user_sender_id', $user->id)
                    ->orWhere('user_receiver_id', $user->id);
            })
            ->get(['user_sender_id', 'user_receiver_id']); // Retrieve both sender and receiver user IDs

        // Collect the related user IDs, making sure to include both user_sender_id and user_receiver_id
        $relatedUserIds = $relatedUserIds->flatMap(function ($item) {
            // Return both sender and receiver IDs as a collection
            return [$item->user_sender_id, $item->user_receiver_id];
        })->unique()->values()->toArray(); // Flatten, remove duplicates, and convert to array

        // // Ensure the logged-in user (user_id) is included
        //     // If the logged-in user isn't already included, you can manually add it
        // if (!in_array($user->id, $relatedUserIds)) {
        //     $relatedUserIds[] = $user->id;
        // }

        // Log the related user IDs for debugging
//      Log::info("The user MR are: " . json_encode($relatedUserIds));

        // Return the final list of related user IDs
        return $relatedUserIds;

    }
}
