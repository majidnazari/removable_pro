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
use Exception;
use Log;

final class CreateParent
{
    use AuthUserTrait, DuplicateCheckTrait, FindOwnerTrait, PersonAncestryWithCompleteMerge, SmallClanTrait;

    public function resolveParent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        $personId = $args['person_id'];

        // Validate if person exists
        $person = Person::find($personId);
        if (!$person) {
            throw new Exception("Person not found.");
        }

        DB::beginTransaction();
        try {
            // Create father and mother
            $fatherData = $this->preparePersonData($args['father'], 1);
            $motherData = $this->preparePersonData($args['mother'], 0);

            $this->checkDuplicate(new Person(), $fatherData);
            $this->checkDuplicate(new Person(), $motherData);

            $father = Person::create($fatherData);
            $mother = Person::create($motherData);

            // Validate age differences
            $this->validateAgeDifference($person, $father, $mother);

            // Create marriage relation
            $marriageData = $this->prepareMarriageData($args, $father->id, $mother->id);
            $this->checkDuplicate(new PersonMarriage(), $marriageData);
            $marriage = PersonMarriage::create($marriageData);

            // Validate marriage-child birth relation
            $this->validateMarriageBirthDates($person->birth_date, $marriage);

            // Validate person ancestry and permissions
            $this->validatePersonAncestry($personId);

            // Create child relation
            $childRelationData = $this->prepareChildRelationData($args, $personId, $marriage->id);
            $this->checkDuplicate(new PersonChild(), ["child_id" => $personId]);
            $this->checkDuplicate(new PersonChild(), $childRelationData);
            $childRelation = PersonChild::create($childRelationData);

            DB::commit();

            return compact('father', 'mother', 'marriage', 'childRelation');

        } catch (Exception $e) {
            DB::rollBack();
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }

    private function preparePersonData(array $data, int $gender): array
    {
        return [
            "creator_id" => $this->userId,
            "node_code" => Carbon::now()->format('YmdHisv') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),

            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "gender" => $gender,
            "birth_date" => $data['birth_date'] ?? null,
            "death_date" => $data['death_date'] ?? null,
            "is_owner" => 0,
            "status" => Status::Active->value,
        ];
    }

    private function validateAgeDifference($person, $father, $mother)
    {
        $childBirthDate = Carbon::parse($person->birth_date);
        $fatherBirthDate = Carbon::parse($father->birth_date);
        $motherBirthDate = Carbon::parse($mother->birth_date);

        if ($fatherBirthDate->diffInYears($childBirthDate) < 12) {
            throw new Exception("Child's birth date must be at least 12 years after the father's birth date.");
        }

        if ($motherBirthDate->diffInYears($childBirthDate) < 9) {
            throw new Exception("Child's birth date must be at least 9 years after the mother's birth date.");
        }
    }

    private function prepareMarriageData(array $args, int $fatherId, int $motherId): array
    {
        return [
            "creator_id" => $this->userId,
            "man_id" => $fatherId,
            "woman_id" => $motherId,
            "marriage_status" => $args['marriage_status'] ?? MarriageStatus::Related->value,
            "status" => $args['status'] ?? Status::Active->value,
            "marriage_date" => $args['marriage_date'] ?? null,
            "divorce_date" => $args['divorce_date'] ?? null,
        ];
    }

    private function validateMarriageBirthDates($childBirthDate, $marriage)
    {
        $childBirthDate = Carbon::parse($childBirthDate);

        if ($marriage->marriage_date) {
            $marriageDate = Carbon::parse($marriage->marriage_date);
            if ($childBirthDate->lt($marriageDate->addMonths(6))) {
                throw new Exception("Child's birth date must be at least 6 months after the marriage date.");
            }
        }

        if ($marriage->divorce_date) {
            $divorceDate = Carbon::parse($marriage->divorce_date);
            if ($childBirthDate->gt($divorceDate->copy()->addMonths(8))) {
                throw new Exception("Child's birth date cannot be more than 8 months after the divorce date.");
            }
        }
    }

    private function validatePersonAncestry(int $personId)
    {
        $result = $this->getPersonAncestryWithCompleteMerge($this->userId,10);
        Log::info("theuser logggd in is :" . $this->userId . "the result of active complete is :" . json_encode( $result['heads']));
        $headsIds = collect($result['heads'])->pluck("person_id")->toArray();

        if (!in_array($personId, $headsIds)) {
            throw new Exception("Person with ID {$personId} not found in the list of heads."); 
        }

        // $usersInSmallClan = $this->getAllUserIdsSmallClan($personId);
        // if (!in_array($this->userId, $usersInSmallClan)) {
        //     throw new Exception("User does not have permission to modify this person.");
        // }

        $getAllusersInSmallClan = $this->getAllUserIdsSmallClan($personId);
       
        if (!is_null($getAllusersInSmallClan) && is_array($getAllusersInSmallClan) && count($getAllusersInSmallClan) > 0) {
            if (!in_array($this->userId, $getAllusersInSmallClan)) {
                throw new Exception("The user logged doesn't have permission to change this person.");
            }
        }
    }

    private function prepareChildRelationData(array $args, int $personId, int $marriageId): array
    {
        return [
            "creator_id" => $this->userId,
            "person_marriage_id" => $marriageId,
            "child_id" => $personId,
            "child_kind" => $args['child_kind'] ?? ChildKind::DirectChild->value,
            "child_status" => $args['child_status'] ?? ChildStatus::WithFamily->value,
            "status" => $args['status'] ?? Status::Active->value,
        ];
    }
}
