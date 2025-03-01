<?php

namespace App\Traits;

use App\Models\UserMergeRequest;
use App\GraphQL\Enums\RequestStatusReceiver;
use App\GraphQL\Enums\RequestStatusSender;
use App\GraphQL\Enums\MergeStatus;

trait PersonAncestryWithActiveMerge
{
    public function getPersonAncestryWithActiveMerge($user_id, $depth = 3)
    {
        // Fetch the UserMergeRequest where the user is either a sender or receiver
        $relation = UserMergeRequest::where(function ($query) use ($user_id) {
            $query->where('user_sender_id', $user_id)
                ->orWhere('user_receiver_id', $user_id);
        })
        ->where(function ($query) {
            $query->where('request_status_sender', RequestStatusSender::Active)
                ->where('request_status_receiver', RequestStatusReceiver::Active)
                ->where('status', '!=', MergeStatus::Complete);
        })
        ->first();

        // If no relationship is found, return only the user's own ancestry
        if (!$relation) {
            $minePerson = $this->findOwner($user_id);

            if (!$minePerson) {
                return null;
            }

            [$mineAncestry, $rootAncestors] = $minePerson->getFullBinaryAncestry($depth);

            return [
                'mine' => $mineAncestry,
                'related_node' => null,
                'heads' => $rootAncestors
            ];
        }

        // Determine if the user is acting as a sender or receiver
        $isSender = $relation->user_sender_id === $user_id;
        $mineUserId = $isSender ? $relation->user_sender_id : $relation->user_receiver_id;
        $relatedUserId = $isSender ? $relation->user_receiver_id : $relation->user_sender_id;

        // Fetch the owners of both the sender and receiver nodes
        $minePerson = $this->findOwner($mineUserId);
        $relatedPerson = $this->findOwner($relatedUserId);

        if (!$minePerson || !$relatedPerson) {
            return null;
        }

        // Fetch the ancestry tree for both "mine" and "related_node"
        [$mineAncestry, $rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
        [$relatedAncestry, $rootAncestors] = $relatedPerson->getFullBinaryAncestry($depth);

        return [
            'mine' => $mineAncestry,
            'related_node' => $relatedAncestry,
            'heads' => $rootAncestors
        ];
    }
}
