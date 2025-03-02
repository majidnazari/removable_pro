<?php

namespace App\GraphQL\Mutations\UserRelation;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\PersonDescendantsWithCompleteMerge;
use Illuminate\Support\Facades\DB;


use Exception;
use Log;

final class CreateUserRelation
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use PersonDescendantsWithCompleteMerge;

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

        $heads = $this->getAllHeads($user_id, $depth);

        // If the count of heads is greater than 1, insert into user_relations and update users
        if (count($heads) > 1) {
            // Begin transaction
            DB::beginTransaction();

            try {
                foreach ($heads as $head) {
                    // Insert into user_relations
                    $this->createUserRelation($user_id, $head);

                    // Update blood_relation column in users table
                    $this->updateBloodRelation($head);
                }

                // Commit the transaction if everything was successful
                DB::commit();
                Log::info("Transaction committed successfully.");
            } catch (Exception $e) {
                // Rollback the transaction if any exception occurs
                DB::rollBack();
                Log::error("Transaction failed: " . $e->getMessage());
            }
        }

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

}