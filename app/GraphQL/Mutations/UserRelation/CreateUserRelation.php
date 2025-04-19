<?php

namespace App\GraphQL\Mutations\UserRelation;

use App\Models\UserDetail;
use App\Models\UserRelation;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\GetAllBloodUsersRelationInClanFromHeads;
use App\Traits\GetAllUsersRelationInClanFromHeads;
use Illuminate\Support\Facades\DB;
use App\Models\User;


use Exception;
use Log;

final class CreateUserRelation
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllBloodUsersRelationInClanFromHeads;
    // use GetAllUsersRelationInClanFromHeads;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveBloodUserRelation($root, array $args)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        $userIds = $this->getAllBloodUsersInClanFromHeads($user_id, $depth);

        $userBloodRelation = User::where("id", $user_id)->first()->blood_user_relation_calculated;

        // If the count of heads is greater than 1, insert into user_relations and update users
        if (count($userIds) >= 1 && (!$userBloodRelation)) {

            // Begin transaction
            DB::beginTransaction();

            try {
                foreach ($userIds as $userId) {

                    $relationExists = UserRelation::where('creator_id', $user_id)
                        ->where('related_with_user_id', $userId)
                        ->exists();

                    // If the relation doesn't exist, insert it
                    if (!$relationExists) {
                        $this->createUserRelation($user_id, $userId);
                    }

                }
                // Update blood_relation column in users table
                $this->updateBloodRelation($user_id);
                // Commit the transaction if everything was successful
                DB::commit();

            } catch (Exception $e) {
                // Rollback the transaction if any exception occurs
                DB::rollBack();
                Log::error("Transaction failed: " . $e->getMessage());
            }
        }
        $userRelations = $this->getBloodUserRelation($user_id);

        return $userRelations;

    }

    public function createUserRelation($user_id, $related_user_id)
    {
        // Insert the relation record into user_relations
        DB::table('user_relations')->insert([
            'creator_id' => $user_id,
            'related_with_user_id' => $related_user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public function updateBloodRelation($user_id)
    {
        // Update blood_relation to true for the user
        DB::table('users')
            ->where('id', $user_id)
            ->update(['blood_user_relation_calculated' => true]);
    }

    public function getBloodUserRelation($user_id)
    {
        // Update blood_relation to true for the user
        return UserRelation::where('creator_id', $user_id);
    }
    public function getBloodUserOtherHandRelation($user_id)
    {
        // Update blood_relation to true for the user
        return UserRelation::where('related_with_user_id', $user_id);
    }

}