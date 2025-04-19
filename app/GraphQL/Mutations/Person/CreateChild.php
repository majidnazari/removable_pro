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
use App\Exceptions\CustomValidationException;


use Exception;
use Log;

final class CreateChild
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use SmallClanTrait;

    public function resolveChild($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();


        DB::beginTransaction(); // Start transaction

        try {
            $manId = $args['man_id'];
            $womanId = $args['woman_id'];


            // Check if the child (person) exists
            $man = Person::find($manId);
            if (!$man) {
                throw new CustomValidationException("man not found", "مرد پیدا نشد", 404);

                //throw new \Exception("man not found");
            }
            // Check if the child (person) exists
            $woman = Person::find($womanId);
            if (!$woman) {
                throw new CustomValidationException("woman not found", "زن پیدا نشد", 404);

                //throw new \Exception("woman not found");
            }

            // $allUsers = $this->getAllUserIdsSmallClan($manId);
//           Log::info("the all users in small clan are:" . json_encode($allUsers) . " and the users logged in {$this->userId}");

            // if (!empty($allUsers) && in_array($this->userId, $allUsers) == false) {
            //     throw new \Exception("you don't have permision to add node!.");

            // }

            $getAllusersInSmallClan = $this->getAllUserIdsSmallClan($manId);
//           Log::info("the getAllusersInSmallClan are". json_encode(value: $getAllusersInSmallClan). "and the condition i s:" );
//           Log::info("the  user id is {$this->userId} and the users in clan are:". json_encode($getAllusersInSmallClan) . " and the conditions is". !in_array($this->userId,$getAllusersInSmallClan));


            if (!is_null($getAllusersInSmallClan) && is_array($getAllusersInSmallClan) && count($getAllusersInSmallClan) > 0) {
                if (!in_array($this->userId, $getAllusersInSmallClan)) {
                    throw new CustomValidationException("The user logged doesn't have permission to change this person.", "کاربری که وارد سیستم شده است، اجازه تغییر این شخص را ندارد.", 404);

                    //throw new \Exception("The user logged doesn't have permission to change this person.");
                }
            }

            // Create the child
            $child = [
                "creator_id" => $this->userId,
                "node_code" => Carbon::now()->format('YmdHisv') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                "first_name" => $args['child']['first_name'],
                "last_name" => $args['child']['last_name'],
                "gender" => $args['child']['gender'],
                "birth_date" => $args['child']['birth_date'] ?? null,
                "death_date" => $args['child']['death_date'] ?? null,
                "is_owner" => 0,
                "status" => Status::Active,
            ];
            $this->checkDuplicate(new Person(), $child);
            $child_created = Person::create($child);

            $marriage = PersonMarriage::where(['man_id' => $manId, 'woman_id' => $womanId])->where('status', Status::Active)->first();
            if (!$marriage) {
                throw new CustomValidationException("marriage not found", "ازدواج پیدا نشد", 404);

                //throw new \Exception("marriage not found");
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
            $this->checkDuplicate(new PersonChild(), $PersonChildModel);


            $childRelation = PersonChild::create($PersonChildModel);

            DB::commit(); // Commit transaction

            return $child_created;

        } catch (CustomValidationException $e) {
            DB::rollBack();
            Log::error("Failed to create child: " . $e->getMessage());

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);
        } catch (Exception $e) {
            DB::rollBack(); // Rollback on failure
            Log::error("Failed to create child: " . $e->getMessage());
            return \GraphQL\Error\Error::createLocatedError($e->getMessage());
        }
    }


}
