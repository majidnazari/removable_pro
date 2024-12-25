<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\GraphQL\Enums\AuthAction;
use GraphQL\Error\Error;
use Exception;


use Log;

trait AuthorizesMutation
{
    protected $user;

    public function __construct()
    {
        //$this->user = $this->getUser();
    }

    /**
     * Get a model based on user authorization.
     * 
     * @param string $modelClass The model class to be queried (e.g., 'App\Models\User')
     * @param array  $args       The query arguments
     * @return Model|null        The found model instance or null
     */
    protected function userAccessibility(string $modelClass, AuthAction $action, array $args, ?array $columnsToCheck = null): mixed
    {
        $this->user = $this->getUser();

        //Log::info("the user loggee din is:" . $this->user);
        if ($this->user->isAdmin() || $this->user->isSupporter()) {
            // Admins and Supporters can perform any action
            return true;
        }

        // if (in_array($action, [AuthAction::Update, AuthAction::Delete])) {
        //     $modelInstance = (new $modelClass)->findOrFail($args['id']);
        //    // Log::info("the model  loggee  is:" . json_encode($modelInstance));

        //     // For update and delete, ensure the user is the creator
        //     $creatorColumn = 'creator_id'; // You can adjust this to your needs (e.g., creator_id, user_id, etc.)
        //     if ($modelInstance->{$creatorColumn} != $this->user->id) {
        //         throw new Exception("You are not authorized to perform this action.");
        //     }
        // }

        // return true;

        // Default to checking only 'creator_id' if no columns are provided
        $columnsToCheck = $columnsToCheck ?? ['creator_id'];

        if (in_array($action, [AuthAction::Update, AuthAction::Delete])) {
            $modelInstance = (new $modelClass)->findOrFail($args['id']);

            // Loop through each column to check if the user matches
            $isAuthorized = false;
            foreach ($columnsToCheck as $column) {
                // Check if the model's column matches the logged-in user's ID
                if ($modelInstance->{$column} == $this->user->id) {
                    $isAuthorized = true;
                    break;
                }
            }

            if (!$isAuthorized) {
                throw new Exception("You are not authorized to perform this action.");
            }
        }

        return true;
    }
}
