<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\GraphQL\Enums\MarriageStatus;
use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;


final class CreatePersonMarriage
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;

    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        $PersonMarriageModel = [
            "creator_id" => $this->userId,

            "man_id" => $args['man_id'],
            "woman_id" => $args['woman_id'],
            "editor_id" => $args['editor_id'] ?? null,
            "marriage_status" => $args['marriage_status'] ?? MarriageStatus::Related, // Default to 'None' if not provided
            //"marriage_status" => $args['spouse_status'] ?? 'None', // Default to 'None' if not provided
            "status" => $args['status'] ?? Status::Active, // Default to 'Active' if not provided
            "marriage_date" => $args['marriage_date'] ?? null,
            "divorce_date" => $args['divorce_date'] ?? null
        ];

        // Check if a similar record exists based on unique constraints or business logic
        //     $is_exist = PersonMarriage::where('man_id', $args['man_id'])
        //     ->where('woman_id', $args['woman_id'])
        //     ->where('marriage_status', $args['marriage_status'] ?? MarriageStatus::None)
        //    // ->where('spouse_status', $args['spouse_status'] ?? 'None')
        //     ->first();

        //     if ($is_exist) {
        //         return Error::createLocatedError("PersonMarriage-CREATE-RECORD_IS_EXIST");
        //     }

        $this->checkDuplicate(new PersonMarriage(), $PersonMarriageModel);
        $PersonMarriageResult = PersonMarriage::create($PersonMarriageModel);
        return $PersonMarriageResult;
    }
}