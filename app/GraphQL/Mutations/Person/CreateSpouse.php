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

final class CreateSpouse
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    public function resolveSpouse($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
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
            $spouse = [
                "creator_id" => $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv'),
                "first_name" => $args['spouse']['first_name'],
                "last_name" => $args['spouse']['last_name'],
                "birth_date" => $args['spouse']['birth_date'] ?? null,
                "death_date" => $args['spouse']['death_date'] ?? null,
                "gender" =>  $person->gender == 1 ? 0 : 1,
                "is_owner"=> false,
                "status" => Status::Active,
            ];
            $this->checkDuplicate(new Person(),  $spouse);
            $spouse_created=Person::create($spouse);

            $man_id= $person->gender==1 ? $person->id :  $spouse_created->id;
            $woman_id= $person->gender==0 ? $person->id :  $spouse_created->id;
            // Create marriage relation
            $PersonMarriageModel = [
                "creator_id" => $this->userId,
                "man_id" => $man_id,
                "woman_id" => $woman_id,
              //  "editor_id" => $args['editor_id'] ?? null,
                "marriage_status" => $args['marriage_status'] ?? MarriageStatus::Related,
                "status" => $args['status'] ?? Status::Active,
                "marriage_date" => $args['marriage_date'] ?? null,
                "divorce_date" => $args['divorce_date'] ?? null
            ];
            $this->checkDuplicate(new PersonMarriage(), $PersonMarriageModel);
            $marriage = PersonMarriage::create($PersonMarriageModel);

          

            DB::commit(); // Commit transaction

            return $marriage;

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on failure
            Log::error("Failed to create parents: " . $e->getMessage());
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }


}
