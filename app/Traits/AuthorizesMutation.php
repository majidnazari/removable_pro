<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\GraphQL\Enums\AuthAction;
use GraphQL\Error\Error;
use App\Exceptions\CustomValidationException;
use App\Traits\AuthUserTrait;
use Exception;


use Log;

trait AuthorizesMutation
{
    use AuthUserTrait;
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
        $modelInstance = null;

        // Default to checking only 'creator_id' if no columns are provided
        $columnsToCheck = $columnsToCheck ?? ['creator_id'];

        if (in_array($action, [AuthAction::Update, AuthAction::Delete])) {
            $modelInstance = (new $modelClass)->findOrFail($args['id']);
            if (!$modelInstance) {

                throw new CustomValidationException("Model is not found", "مدل مربوطه پیدا نشد.", 400);
            }
            if ($this->user->isAdmin() || $this->user->isSupporter()) {
                // Admins and Supporters can perform any action
                return $modelInstance;
            }
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

                throw new CustomValidationException("You are not authorized to perform this action.", "شما مجاز به انجام این عملیات نیستید.", 403);
            }
        }

        return $modelInstance;
    }
}
