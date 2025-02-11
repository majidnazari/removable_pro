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

final class CreateParent
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    public function resolveParent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        DB::beginTransaction(); // Start transaction

        try {
            $personId = $args['person_id'];

            // Check if the child (person) exists
            $person = Person::find($personId);
            if (!$person) {
                throw new \Exception("Person not found");
            }

            // Create the father
            $father = [
                "creator_id" => $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv'),
                "first_name" => $args['father']['first_name'],
                "last_name" => $args['father']['last_name'],
                "gender" => 1, // Male
                "birth_date" => $args['father']['birth_date'] ?? null,
                "death_date" => $args['father']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,
            ];
            $this->checkDuplicate(new Person(),  $father);
            $father_created=Person::create($father);

            // Create the mother
            $mother = [
                "creator_id" =>  $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv'),
                "first_name" => $args['mother']['first_name'],
                "last_name" => $args['mother']['last_name'],
                "gender" => 0, // Female
                "birth_date" => $args['mother']['birth_date'] ?? null,
                "death_date" => $args['mother']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,

            ];
            $this->checkDuplicate(new Person(),  $mother);
            $mother_created=Person::create($mother);

            // Create marriage relation
            $PersonMarriageModel = [
                "creator_id" => $this->userId,
                "man_id" => $father_created->id,
                "woman_id" => $mother_created->id,
              //  "editor_id" => $args['editor_id'] ?? null,
                "marriage_status" => $args['marriage_status'] ?? MarriageStatus::Related,
                "status" => $args['status'] ?? Status::Active,
                "marriage_date" => $args['marriage_date'] ?? null,
                "divorce_date" => $args['divorce_date'] ?? null
            ];
            $this->checkDuplicate(new PersonMarriage(), $PersonMarriageModel);
            $marriage = PersonMarriage::create($PersonMarriageModel);

            // Link the child to this marriage
            $PersonChildModel = [
                "creator_id" => $this->userId,
                //"editor_id" => $args['editor_id'] ?? null,
                "person_marriage_id" => $marriage->id,
                "child_id" => $personId,
                "child_kind" => $args['child_kind'] ?? ChildKind::DirectChild,
                "child_status" => $args['child_status'] ?? ChildStatus::WithFamily,
                "status" => $args['status'] ?? Status::Active
            ];
            $this->checkDuplicate(new PersonChild(),  $PersonChildModel);
            $childRelation = PersonChild::create($PersonChildModel);

            DB::commit(); // Commit transaction

            return [
                "father" => $father,
                "mother" => $mother,
                "marriage" => $marriage,
                "childRelation" => $childRelation
            ];

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on failure
            Log::error("Failed to create parents: " . $e->getMessage());
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }


}
