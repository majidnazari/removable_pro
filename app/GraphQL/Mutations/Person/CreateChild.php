<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\MarriageStatus;
use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Log;

final class CreateChild
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    public function resolveChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        DB::beginTransaction(); // Start transaction

        try {
            $manId = $args['man_id'];
            $womanId = $args['woman_id'];

            // Check if the child (person) exists
            $man = Person::find($manId);
            if (!$man) {
                throw new \Exception("man not found");
            }
            // Check if the child (person) exists
            $woman = Person::find($womanId);
            if (!$woman) {
                throw new \Exception("woman not found");
            }

            // Create the child
            $child = [
                "creator_id" => $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv'),
                "first_name" => $args['child']['first_name'],
                "last_name" => $args['child']['last_name'],
                "gender" => $args['child']['gender'],
                "birth_date" => $args['child']['birth_date'] ?? null,
                "death_date" => $args['child']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,
            ];
            $this->checkDuplicate(new Person(),  $child);
            $child_created=Person::create($child);

            $marriage = PersonMarriage::where(['man_id' => $manId, 'woman_id' => $womanId])->where('status',Status::Active)->first();
            if (!$marriage) {
                throw new \Exception("marriage not found");
            }
            // Link the child to this marriage
            $PersonChildModel = [
                "creator_id" => $this->userId,
                //"editor_id" => $args['editor_id'] ?? null,
                "person_marriage_id" => $marriage->id,
                "child_id" => $child_created->id,
                "child_kind" => $args['child_kind'] ?? ChildKind::DirectChild,
                "child_status" => $args['child_status'] ?? ChildStatus::WithFamily,
                "status" => $args['status'] ?? Status::Active
            ];
            $this->checkDuplicate(new PersonChild(),  $PersonChildModel);
            $childRelation = PersonChild::create($PersonChildModel);

            DB::commit(); // Commit transaction

            return $child_created;

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on failure
            Log::error("Failed to create parents: " . $e->getMessage());
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }


}
