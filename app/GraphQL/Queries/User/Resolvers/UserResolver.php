<?php
namespace App\GraphQL\Queries\User\Resolvers;

use App\Models\User;
use App\Models\Notif;
use Illuminate\Support\Facades\Auth;

class UserResolver
{
    public function notifs($root, $args, $context)
    {

        return $root->notifs()->notRead()->get();
        // $authUser = Auth::user();

        // // If admin or supporter, show all unread notifications for this user
        // if ($authUser->isAdmin() || $authUser->isSupporter()) {
        //     return $root->notifs()->notRead()->get();
        // }

        // // If a normal user, ensure they are viewing their own notifications
        // if ($authUser->id !== $root->id) {
        //     // Not authorized to view another user's notifications
        //     return [];
        // }

        // // For normal user, show only their unread notifications
        // return $root->notifs()->notRead()->get();
    }
}
