<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\MergeStatus;
use App\Traits\FindOwnerTrait;


trait PersonAncestryWithCompleteMerge
{
    use FindOwnerTrait;
    public function getPersonAncestryWithCompleteMerge($user_id, $depth = 3)
    {
        // Fetch all UserMergeRequests with Complete status
        $relations = UserMergeRequest::where(function ($query) use ($user_id) {
            $query->where('user_sender_id', $user_id)
                ->orWhere('user_receiver_id', $user_id);
        })
            ->where('status', MergeStatus::Complete)
            ->get();

        // If no relationships, return only the user's own ancestry
        $minePerson = $this->findOwner($user_id);
        if (!$minePerson) {
            return null;
        }

        [$mineAncestry, $rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
        $relatedNodes = [];

        foreach ($relations as $relation) {
            // Determine related user ID
            $relatedUserId = $relation->user_sender_id === $user_id
                ? $relation->user_receiver_id
                : $relation->user_sender_id;

            // Fetch the related person's ancestry
            $relatedPerson = $this->findOwner($relatedUserId);
            if ($relatedPerson) {
                [$relatedNodes[]] = $relatedPerson->getFullBinaryAncestry($depth);
            }
        }

        return [
            'mine' => $mineAncestry,
            'related_nodes' => $relatedNodes,
            'heads' => $rootAncestors
        ];
    }
}
