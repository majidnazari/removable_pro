<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserMergeRequest;
use Illuminate\Auth\Access\Response;

class UserMergeRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserMergeRequest $userMergeRequest): bool
    {
        // Admins can view all requests
        if ($user->isAdmin()) {
            return true;
        }

        // Normal users can only view requests where they are sender or receiver
        return $userMergeRequest->user_sender_id === $user->id || $userMergeRequest->user_receiver_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserMergeRequest $userMergeRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserMergeRequest $userMergeRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserMergeRequest $userMergeRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserMergeRequest $userMergeRequest): bool
    {
        //
    }
}
