<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Traits\SmallClanTrait;
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
use App\Traits\FindOwnerTrait;
use App\Traits\PersonAncestryWithCompleteMerge;
use Log;

final class CreateParent_old
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use FindOwnerTrait;
    use PersonAncestryWithCompleteMerge;
    use SmallClanTrait;

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
                "node_code" => Carbon::now()->format('YmdHisv') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),

                "first_name" => $args['father']['first_name'],
                "last_name" => $args['father']['last_name'],
                "gender" => 1, // Male
                "birth_date" => $args['father']['birth_date'] ?? null,
                "death_date" => $args['father']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,
            ];
            $this->checkDuplicate(new Person(), $father);
            $father_created = Person::create($father);

            // Create the mother
            $mother = [
                "creator_id" => $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),

                "first_name" => $args['mother']['first_name'],
                "last_name" => $args['mother']['last_name'],
                "gender" => 0, // Female
                "birth_date" => $args['mother']['birth_date'] ?? null,
                "death_date" => $args['mother']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,

            ];
            $this->checkDuplicate(new Person(), $mother);
            $mother_created = Person::create($mother);

            // Check the age of the person
            $childBirthDate = Carbon::parse($person->birth_date);

            //Log::info("the child birthdtae:{$childBirthDate}");

            $fatherBirthDate = Carbon::parse($father_created->birth_date);
            // Ensure the child is at least 12 years younger than the father
            $ageDifference = $fatherBirthDate->diffInYears($childBirthDate);
            if ($ageDifference < 12) {
                throw new \Exception("The child's birth date must be at least 12 years after the father's birth date.");
            }

            $motherBirthDate = Carbon::parse($mother_created->birth_date);
            // Ensure the child is at least 9 years younger than the mother
            $ageDifference = $motherBirthDate->diffInYears($childBirthDate);
            if ($ageDifference < 9) {
                throw new \Exception("The child's birth date must be at least 9 years after the mother's birth date.");
            }
            // Log::info("the motherBirthDate birthdtae:{$motherBirthDate}");


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



            // Extract the birth date for validation
            $childBirthDate = Carbon::parse($person->birth_date);

            // Validate child birth date based on marriage date
            if ($marriage->marriage_date) {
                $marriageDate = Carbon::parse($marriage->marriage_date);

                // Ensure child's birth date is at least 6 months after the marriage date
                if ($childBirthDate->lt($marriageDate->addMonths(6))) {
                    Log::info("the marriage date is {$marriageDate} and the child birthdate is {$childBirthDate}");
                    throw new \Exception("Child's birth date must be at least 6 months after the marriage date.");
                }
            }

            if ($marriage->divorce_date) {
                $divorceDate = Carbon::parse($marriage->divorce_date);

                // Calculate the date that is 8 months after the divorce date
                $maxChildBirthDate = $divorceDate->copy()->addMonths(8);

                // Check if the child's birth date is after the maximum allowed date
                if ($childBirthDate->gt($maxChildBirthDate)) {
                    throw new \Exception("Child's birth date cannot be more than 8 months after the divorce date.");
                }
            }

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
            $this->checkDuplicate(new PersonChild(), ["child_id" => $personId]); // check has parent before or not!.

            $this->checkDuplicate(new PersonChild(), $PersonChildModel);
            $result = $this->getPersonAncestryWithCompleteMerge($this->userId);
            Log::info("the result of with complete ancestry  is:" . json_encode($result['heads']));

            $allheads = $result['heads'];
            //Log::info("the allheads is". json_encode( $allheads));

            $headsids = collect($allheads)->pluck("person_id")->toArray();
            //Log::info("the heads are". json_encode($headsids));

            if (!in_array($personId, $headsids)) {
                throw new \Exception("The person with ID {$personId} was not found in the list of heads.");
            }
            $getAllusersInSmallClan = $this->getAllUserIdsSmallClan($personId);
            Log::info("the getAllusersInSmallClan are" . json_encode(value: $getAllusersInSmallClan));
            //Log::info("the  user id is {$this->userId} and the users in clan are:". json_encode($getAllusersInSmallClan) . " and the conditions is". !in_array($this->userId,$getAllusersInSmallClan));


            if (!is_null($getAllusersInSmallClan) && is_array($getAllusersInSmallClan) && count($getAllusersInSmallClan) > 0) {
                if (!in_array($this->userId, $getAllusersInSmallClan)) {
                    throw new \Exception("The user logged doesn't have permission to change this person.");
                }
            }

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
            //Log::error("Failed to create parents: " . $e->getMessage());
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }


}
